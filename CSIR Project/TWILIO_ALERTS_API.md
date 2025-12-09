# Twilio API for Sending Alerts - Complete Guide

## What is the Twilio API?

The **Twilio API** is a set of programming tools that lets you send SMS, calls, and other messages from your code.

Think of it like:
- **Email API**: Send emails programmatically
- **Twilio API**: Send SMS/calls programmatically
- **Same concept, different medium**

---

## Finding Twilio API Documentation

### Official Twilio API Docs
- **Main Documentation**: https://www.twilio.com/docs/sms/api
- **API Reference**: https://www.twilio.com/docs/sms/send-messages
- **Node.js Library**: https://www.twilio.com/docs/libraries/node

### Key API Endpoints

```
SMS Sending API:
POST https://api.twilio.com/2010-04-01/Accounts/{AccountSid}/Messages.json

Parameters:
- From: Your Twilio phone number
- To: Recipient's phone number
- Body: Message content
- MediaUrl: (Optional) Media attachment
```

---

## Method 1: Using Twilio Node.js Library (Easiest)

### Installation
```bash
npm install twilio
```

### Basic SMS Alert Example
```javascript
const twilio = require('twilio');

// Your credentials from Twilio Console
const accountSid = 'AC1234567890abcdefghijklmnopqrst';
const authToken = 'your-auth-token-here';
const fromNumber = '+15551234567';

// Create Twilio client
const client = twilio(accountSid, authToken);

// Send phishing alert
async function sendPhishingAlert(userPhone, threatType, detectedUrl) {
  try {
    const message = await client.messages.create({
      body: `🚨 PHISHGUARD ALERT: Phishing threat detected!\n\nThreat: ${threatType}\nURL: ${detectedUrl}\n\nDo not click this link!`,
      from: fromNumber,
      to: userPhone
    });
    
    console.log('Alert sent! Message SID:', message.sid);
    return { success: true, messageSid: message.sid };
  } catch (error) {
    console.error('Error sending alert:', error);
    return { success: false, error: error.message };
  }
}

// Usage
sendPhishingAlert('+1 (555) 987-6543', 'secure-update-login', 'http://phishing-site.com');
```

---

## Method 2: Using Twilio REST API (Direct HTTP Calls)

### Using cURL (Command Line)
```bash
curl -X POST https://api.twilio.com/2010-04-01/Accounts/AC1234567890abcdefghijklmnopqrst/Messages.json \
  -d "From=+15551234567" \
  -d "To=+15559876543" \
  -d "Body=Alert: Phishing threat detected at http://phishing-site.com" \
  -u AC1234567890abcdefghijklmnopqrst:your-auth-token
```

### Using JavaScript (Fetch)
```javascript
async function sendAlertWithFetch(userPhone, message) {
  const accountSid = 'AC1234567890abcdefghijklmnopqrst';
  const authToken = 'your-auth-token';
  const fromNumber = '+15551234567';
  
  // Create Basic Auth header
  const auth = btoa(`${accountSid}:${authToken}`);
  
  const url = `https://api.twilio.com/2010-04-01/Accounts/${accountSid}/Messages.json`;
  
  const formData = new FormData();
  formData.append('From', fromNumber);
  formData.append('To', userPhone);
  formData.append('Body', message);
  
  try {
    const response = await fetch(url, {
      method: 'POST',
      headers: {
        'Authorization': `Basic ${auth}`
      },
      body: formData
    });
    
    const result = await response.json();
    console.log('Alert sent:', result);
    return result;
  } catch (error) {
    console.error('Error:', error);
  }
}

// Usage
sendAlertWithFetch('+15559876543', 'Phishing alert: Threat detected!');
```

---

## Method 3: Using Firebase Cloud Functions (Best for PhishGuard)

### Setup
```bash
firebase init functions
cd functions
npm install twilio
```

### Create Alert Function
**File: functions/index.js**
```javascript
const functions = require("firebase-functions");
const admin = require("firebase-admin");
const twilio = require("twilio");

admin.initializeApp();

// Twilio credentials
const accountSid = functions.config().twilio.account_sid;
const authToken = functions.config().twilio.auth_token;
const twilioPhone = functions.config().twilio.phone_number;

const client = twilio(accountSid, authToken);

// Function 1: Send Phishing Detection Alert
exports.sendPhishingAlert = functions.https.onCall(async (data, context) => {
  // Verify user is authenticated
  if (!context.auth) {
    throw new functions.https.HttpsError(
      "unauthenticated",
      "User must be logged in"
    );
  }

  const { threatType, detectedUrl, userPhone } = data;

  // Validate input
  if (!threatType || !detectedUrl || !userPhone) {
    throw new functions.https.HttpsError(
      "invalid-argument",
      "Missing required fields"
    );
  }

  try {
    // Create alert message
    const alertMessage = `🚨 PHISHGUARD ALERT
━━━━━━━━━━━━━━━━━━━━
Threat Detected: ${threatType}
URL: ${detectedUrl}

⚠️ DO NOT CLICK THIS LINK
━━━━━━━━━━━━━━━━━━━━`;

    // Send SMS
    const messageSid = await client.messages.create({
      body: alertMessage,
      from: twilioPhone,
      to: userPhone
    });

    // Log alert in Firestore
    await admin.firestore().collection("alerts").add({
      userId: context.auth.uid,
      threatType: threatType,
      detectedUrl: detectedUrl,
      userPhone: userPhone,
      messageSid: messageSid.sid,
      timestamp: admin.firestore.FieldValue.serverTimestamp(),
      status: "sent"
    });

    return {
      success: true,
      messageSid: messageSid.sid,
      message: "Alert sent successfully"
    };
  } catch (error) {
    // Log error
    console.error("Error sending alert:", error);
    
    throw new functions.https.HttpsError(
      "internal",
      "Failed to send alert: " + error.message
    );
  }
});

// Function 2: Send Verification Code
exports.sendVerificationCode = functions.https.onCall(async (data, context) => {
  if (!context.auth) {
    throw new functions.https.HttpsError(
      "unauthenticated",
      "User must be logged in"
    );
  }

  const { phoneNumber } = data;
  
  // Generate 6-digit code
  const verificationCode = Math.floor(100000 + Math.random() * 900000);

  try {
    // Send SMS with code
    const message = await client.messages.create({
      body: `Your PhishGuard verification code is: ${verificationCode}\n\nDo not share this code with anyone.`,
      from: twilioPhone,
      to: phoneNumber
    });

    // Save code to Firestore (expires in 10 minutes)
    const expiryTime = new Date(Date.now() + 10 * 60 * 1000);
    
    await admin.firestore().collection("verificationCodes").add({
      userId: context.auth.uid,
      phoneNumber: phoneNumber,
      code: verificationCode,
      messageSid: message.sid,
      createdAt: admin.firestore.FieldValue.serverTimestamp(),
      expiresAt: expiryTime,
      verified: false
    });

    return {
      success: true,
      messageSid: message.sid,
      message: "Verification code sent"
    };
  } catch (error) {
    console.error("Error sending verification code:", error);
    
    throw new functions.https.HttpsError(
      "internal",
      "Failed to send verification code: " + error.message
    );
  }
});

// Function 3: Send Daily Report
exports.sendDailyReport = functions.https.onCall(async (data, context) => {
  if (!context.auth) {
    throw new functions.https.HttpsError(
      "unauthenticated",
      "User must be logged in"
    );
  }

  const { phoneNumber, threatCount, scansPerformed } = data;

  try {
    const reportMessage = `📊 PHISHGUARD DAILY REPORT
━━━━━━━━━━━━━━━━━━━━━━
Scans Performed: ${scansPerformed}
Threats Detected: ${threatCount}
Status: 🟢 Protected

Stay vigilant! 🛡️`;

    await client.messages.create({
      body: reportMessage,
      from: twilioPhone,
      to: phoneNumber
    });

    return {
      success: true,
      message: "Daily report sent"
    };
  } catch (error) {
    throw new functions.https.HttpsError(
      "internal",
      "Failed to send report: " + error.message
    );
  }
});

// Function 4: Send Account Activity Alert
exports.sendActivityAlert = functions.https.onCall(async (data, context) => {
  if (!context.auth) {
    throw new functions.https.HttpsError(
      "unauthenticated",
      "User must be logged in"
    );
  }

  const { phoneNumber, activity, location, time } = data;

  try {
    const activityMessage = `🔔 PHISHGUARD ACTIVITY ALERT
━━━━━━━━━━━━━━━━━━━━━━━━
Activity: ${activity}
Location: ${location}
Time: ${time}

If this wasn't you, change your password immediately!`;

    await client.messages.create({
      body: activityMessage,
      from: twilioPhone,
      to: phoneNumber
    });

    return {
      success: true,
      message: "Activity alert sent"
    };
  } catch (error) {
    throw new functions.https.HttpsError(
      "internal",
      "Failed to send activity alert: " + error.message
    );
  }
});
```

### Set Environment Variables
```bash
firebase functions:config:set \
  twilio.account_sid="AC1234567890abcdefghijklmnopqrst" \
  twilio.auth_token="your-auth-token-here" \
  twilio.phone_number="+15551234567"
```

### Deploy Functions
```bash
firebase deploy --only functions
```

---

## Method 4: Call Twilio API from Your Web App

### HTML Form for Alert
```html
<div id="alert-section" style="display: none;">
  <h3>Send Custom Alert</h3>
  <input 
    type="tel" 
    id="alert-phone" 
    placeholder="+1 (555) 123-4567"
  />
  <textarea 
    id="alert-message" 
    placeholder="Enter alert message"
  ></textarea>
  <button onclick="sendCustomAlert()">Send Alert</button>
  <div id="alert-status"></div>
</div>
```

### JavaScript to Call Firebase Function
```javascript
import { 
  httpsCallable 
} from "https://www.gstatic.com/firebasejs/9.22.0/firebase-functions.js";
import { 
  getFunctions 
} from "https://www.gstatic.com/firebasejs/9.22.0/firebase-functions.js";

const functions = getFunctions();

// Send phishing alert when threat detected
async function sendPhishingAlertOnDetection(threatType, detectedUrl, userPhone) {
  try {
    const sendAlert = httpsCallable(functions, "sendPhishingAlert");
    const result = await sendAlert({
      threatType: threatType,
      detectedUrl: detectedUrl,
      userPhone: userPhone
    });
    
    console.log("Alert sent:", result.data);
    showNotification("✅ Alert sent successfully!");
    
    return result.data;
  } catch (error) {
    console.error("Error sending alert:", error);
    showNotification("❌ Failed to send alert: " + error.message);
  }
}

// Send verification code
async function requestVerificationCode(phoneNumber) {
  try {
    const sendCode = httpsCallable(functions, "sendVerificationCode");
    const result = await sendCode({
      phoneNumber: phoneNumber
    });
    
    console.log("Code sent:", result.data);
    showNotification("✅ Verification code sent!");
    
    return result.data;
  } catch (error) {
    console.error("Error:", error);
    showNotification("❌ Error: " + error.message);
  }
}

// Send daily report
async function sendDailyReportAlert(phoneNumber, threatCount, scansPerformed) {
  try {
    const sendReport = httpsCallable(functions, "sendDailyReport");
    const result = await sendReport({
      phoneNumber: phoneNumber,
      threatCount: threatCount,
      scansPerformed: scansPerformed
    });
    
    console.log("Report sent:", result.data);
    return result.data;
  } catch (error) {
    console.error("Error:", error);
  }
}

// Send activity alert
async function sendActivityAlertOnLogin(phoneNumber, location, time) {
  try {
    const sendActivity = httpsCallable(functions, "sendActivityAlert");
    const result = await sendActivity({
      phoneNumber: phoneNumber,
      activity: "Login detected",
      location: location,
      time: time
    });
    
    console.log("Activity alert sent:", result.data);
    return result.data;
  } catch (error) {
    console.error("Error:", error);
  }
}

// Call when phishing is detected during scanning
function scanInput() {
  const input = document.getElementById("inputData").value.toLowerCase();
  
  // ... existing scanning logic ...
  
  if (detectedSignature) {
    // THREAT FOUND
    
    // Get user's phone from Firestore
    const user = window.firebaseAuth.currentUser;
    
    // Fetch user's phone number
    getDoc(doc(db, "users", user.uid)).then(userDoc => {
      if (userDoc.exists() && userDoc.data().phone) {
        // Send alert
        sendPhishingAlertOnDetection(
          detectedSignature,
          input,
          userDoc.data().phone
        );
      }
    });
  }
}
```

---

## Alert Types for PhishGuard

### Alert 1: Phishing Detection
```
🚨 PHISHGUARD ALERT
━━━━━━━━━━━━━━━━━━
Threat: secure-update-login
URL: http://phishing-site.com
⚠️ DO NOT CLICK
```

### Alert 2: Verification Code
```
Your PhishGuard verification code is: 123456

Do not share this code!
```

### Alert 3: Daily Report
```
📊 PHISHGUARD DAILY REPORT
Scans: 15
Threats: 2
Status: 🟢 Protected
```

### Alert 4: Suspicious Login
```
🔔 NEW LOGIN DETECTED
Location: New York, NY
Time: Dec 9, 2025 3:45 PM
Approve this login?
```

### Alert 5: Plan Expiration
```
⏰ PLAN EXPIRATION
Your Pro plan expires in 7 days
Renew now to stay protected
```

---

## API Response Example

### Successful Response
```json
{
  "account_sid": "AC1234567890abcdefghijklmnopqrst",
  "api_version": null,
  "body": "Alert: Phishing threat detected!",
  "date_created": "2025-12-09T15:30:00.000Z",
  "date_updated": "2025-12-09T15:30:00.000Z",
  "date_sent": "2025-12-09T15:30:01.000Z",
  "direction": "outbound-api",
  "error_code": null,
  "error_message": null,
  "from": "+15551234567",
  "messaging_service_sid": null,
  "num_media": "0",
  "num_segments": "1",
  "price": "-0.00750",
  "price_unit": "USD",
  "sid": "SM1234567890abcdef1234567890abcdef",
  "status": "queued",
  "subresource_uris": {
    "media": "/2010-04-01/Accounts/AC1234567890abcdefghijklmnopqrst/Messages/SM1234567890abcdef1234567890abcdef/Media.json"
  },
  "to": "+15559876543",
  "uri": "/2010-04-01/Accounts/AC1234567890abcdefghijklmnopqrst/Messages/SM1234567890abcdef1234567890abcdef.json"
}
```

---

## Twilio API Status Codes

| Status | Meaning |
|--------|---------|
| `queued` | Message waiting to be sent |
| `sent` | Message sent successfully |
| `delivered` | Message delivered to phone |
| `undelivered` | Could not deliver message |
| `failed` | Message failed |

---

## Error Handling

```javascript
async function sendAlertWithErrorHandling(phoneNumber, message) {
  try {
    const sendAlert = httpsCallable(functions, "sendPhishingAlert");
    const result = await sendAlert({
      threatType: "phishing",
      detectedUrl: "example.com",
      userPhone: phoneNumber
    });
    
    return { success: true, data: result.data };
  } catch (error) {
    // Handle different error types
    if (error.code === 'unauthenticated') {
      console.error("User not logged in");
      return { success: false, error: "Please log in first" };
    } else if (error.code === 'invalid-argument') {
      console.error("Invalid phone number");
      return { success: false, error: "Invalid phone number format" };
    } else if (error.code === 'internal') {
      console.error("Server error");
      return { success: false, error: "Failed to send alert" };
    } else {
      console.error("Unknown error:", error);
      return { success: false, error: error.message };
    }
  }
}
```

---

## Rate Limiting (Prevent Spam)

```javascript
// Track alert sends per user
const alertTimestamps = {};

async function sendAlertWithRateLimit(userId, phoneNumber, threatType) {
  const now = Date.now();
  const lastAlertTime = alertTimestamps[userId] || 0;
  const timeSinceLastAlert = now - lastAlertTime;
  
  // Only allow 1 alert per minute per user
  if (timeSinceLastAlert < 60000) {
    throw new Error("Too many alerts. Please wait before sending another.");
  }
  
  // Send alert
  const result = await sendPhishingAlert(phoneNumber, threatType);
  
  // Update timestamp
  alertTimestamps[userId] = now;
  
  return result;
}
```

---

## Complete Integration Checklist

- [ ] Twilio account created
- [ ] Credentials obtained (Account SID, Auth Token, Phone)
- [ ] Firebase Functions initialized
- [ ] Twilio package installed (`npm install twilio`)
- [ ] Alert functions created
- [ ] Environment variables set
- [ ] Functions deployed (`firebase deploy`)
- [ ] HTML forms created
- [ ] JavaScript functions created
- [ ] Error handling implemented
- [ ] Rate limiting added
- [ ] Tested with real phone number
- [ ] Logs set up
- [ ] Production security rules configured

---

## Testing Your Alerts

```javascript
// Test phishing alert
sendPhishingAlertOnDetection(
  "secure-update-login",
  "http://test-phishing.com",
  "+1 (555) 123-4567"
);

// Test verification code
requestVerificationCode("+1 (555) 123-4567");

// Test daily report
sendDailyReportAlert("+1 (555) 123-4567", 3, 25);
```

---

## Useful Links

- **Twilio SMS API**: https://www.twilio.com/docs/sms/api
- **Twilio Node.js Docs**: https://www.twilio.com/docs/libraries/node
- **Firebase Functions**: https://firebase.google.com/docs/functions
- **API Status Dashboard**: https://status.twilio.com/
- **Twilio Console**: https://www.twilio.com/console

---

**Now you have the complete Twilio API guide! 🎉**

Which alert type do you want to implement first?
1. Phishing detection alerts?
2. Verification codes?
3. Daily reports?
4. Activity alerts?
