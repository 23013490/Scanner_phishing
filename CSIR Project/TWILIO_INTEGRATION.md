# How to Include Twilio Account in Your PhishGuard App

## What is Twilio?

**Twilio** is a cloud service that lets you send and receive:
- 📱 **SMS messages** (text messages)
- 📞 **Phone calls**
- 📧 **WhatsApp messages**
- ✉️ **Emails**

**Real-world uses:**
- Send verification codes via SMS
- Send alerts about phishing detections
- Two-factor authentication (2FA)
- User notifications

---

## Step 1: Create a Twilio Account

### 1.1 Sign Up
1. Go to: https://www.twilio.com/try-twilio
2. Click **"Sign up"**
3. Enter your information:
   - Full Name
   - Email
   - Password
   - Phone Number
4. Click **"Create Account"**

### 1.2 Verify Your Account
1. Twilio will send a verification code to your phone via SMS
2. Enter the code
3. Click **"Verify"**

### 1.3 Complete Your Profile
1. Answer questions about your use case:
   - **What are you building?** → "Security/Phishing Detection"
   - **Do you plan to use SMS?** → "Yes"
   - **Do you need voice?** → "No" (optional)
2. Click **"Get Started"**

---

## Step 2: Get Your Twilio Credentials

### 2.1 Find Your Account SID and Auth Token
1. After login, go to the **Dashboard**: https://www.twilio.com/console
2. You'll see your credentials:
   ```
   Account SID:    AC1234567890abcdefghijklmnopqrst
   Auth Token:     your-auth-token-here-secret
   ```

**⚠️ Important**: Keep these secret! Never share them publicly.

### 2.2 Get a Twilio Phone Number
1. In the Dashboard, click **"Phone Numbers"** (left sidebar)
2. Click **"Get your first Twilio phone number"**
3. Accept the terms
4. Choose a phone number for your region
5. Click **"Choose this Number"**
6. Confirm
7. You'll see your number: `+1 (555) 123-4567`

### 2.3 Copy Your Credentials
Save these three things:
```
Account SID:     AC1234567890abcdefghijklmnopqrst
Auth Token:      your-auth-token-here-secret
Phone Number:    +15551234567 (with country code)
```

---

## Step 3: Set Up Twilio in Your Backend

### Option A: Using Node.js (Backend)

#### 3A.1 Install Twilio Package
```bash
npm install twilio
```

#### 3A.2 Create a JavaScript File (send-sms.js)
```javascript
// send-sms.js
const twilio = require('twilio');

const accountSid = 'AC1234567890abcdefghijklmnopqrst';
const authToken = 'your-auth-token-here-secret';
const client = new twilio(accountSid, authToken);

// Function to send SMS
async function sendSMS(toPhoneNumber, message) {
  try {
    const result = await client.messages.create({
      body: message,
      from: '+15551234567',  // Your Twilio number
      to: toPhoneNumber       // Recipient's number (e.g., '+1234567890')
    });
    console.log('SMS sent! Message SID:', result.sid);
    return result;
  } catch (error) {
    console.error('Error sending SMS:', error);
  }
}

// Example: Send SMS
sendSMS('+1 (555) 987-6543', 'Hello! Your verification code is 123456');
```

#### 3A.3 Use in Express Server
```javascript
const express = require('express');
const app = express();

app.post('/send-sms', async (req, res) => {
  const { phone, message } = req.body;
  
  try {
    await sendSMS(phone, message);
    res.json({ success: true, message: 'SMS sent!' });
  } catch (error) {
    res.status(500).json({ success: false, error: error.message });
  }
});

app.listen(3000, () => console.log('Server running on port 3000'));
```

---

## Step 4: Use Twilio with Firebase Functions (Recommended)

### 4.1 Set Up Firebase Functions
```bash
firebase init functions
cd functions
npm install twilio
```

### 4.2 Create a Firebase Function
**File: functions/index.js**
```javascript
const functions = require("firebase-functions");
const admin = require("firebase-admin");
const twilio = require("twilio");

admin.initializeApp();

const accountSid = "AC1234567890abcdefghijklmnopqrst";
const authToken = "your-auth-token-here-secret";
const twilioPhone = "+15551234567";

const client = twilio(accountSid, authToken);

// Function to send SMS verification code
exports.sendVerificationCode = functions.https.onCall(async (data, context) => {
  // Check if user is authenticated
  if (!context.auth) {
    throw new functions.https.HttpsError(
      "unauthenticated",
      "User must be logged in"
    );
  }

  const { phoneNumber } = data;
  const verificationCode = Math.floor(100000 + Math.random() * 900000);

  try {
    // Send SMS
    await client.messages.create({
      body: `Your PhishGuard verification code is: ${verificationCode}`,
      from: twilioPhone,
      to: phoneNumber
    });

    // Save code to Firestore (for verification later)
    await admin
      .firestore()
      .collection("verificationCodes")
      .add({
        userId: context.auth.uid,
        code: verificationCode,
        phone: phoneNumber,
        createdAt: admin.firestore.FieldValue.serverTimestamp(),
        verified: false
      });

    return { success: true, message: "SMS sent!" };
  } catch (error) {
    throw new functions.https.HttpsError("internal", error.message);
  }
});

// Function to verify the code
exports.verifyCode = functions.https.onCall(async (data, context) => {
  if (!context.auth) {
    throw new functions.https.HttpsError(
      "unauthenticated",
      "User must be logged in"
    );
  }

  const { code } = data;

  try {
    // Check if code matches
    const result = await admin
      .firestore()
      .collection("verificationCodes")
      .where("userId", "==", context.auth.uid)
      .where("code", "==", parseInt(code))
      .where("verified", "==", false)
      .limit(1)
      .get();

    if (result.empty) {
      throw new functions.https.HttpsError(
        "not-found",
        "Invalid verification code"
      );
    }

    // Mark as verified
    await result.docs[0].ref.update({ verified: true });

    return { success: true, message: "Phone verified!" };
  } catch (error) {
    throw new functions.https.HttpsError("internal", error.message);
  }
});
```

### 4.3 Deploy Functions
```bash
firebase deploy --only functions
```

---

## Step 5: Call Twilio from Your Web App

### 5.1 Update your HTML
```html
<!-- Phone verification form -->
<div id="phone-verification">
  <h3>Verify Your Phone Number</h3>
  
  <input 
    type="tel" 
    id="phone-input" 
    placeholder="+1 (555) 123-4567"
    required 
  />
  
  <button onclick="sendVerificationCode()">Send Code</button>
  
  <input 
    type="text" 
    id="code-input" 
    placeholder="Enter verification code"
    style="display: none;"
  />
  
  <button 
    id="verify-btn" 
    onclick="verifyPhoneCode()" 
    style="display: none;"
  >
    Verify Code
  </button>
  
  <div id="verification-status"></div>
</div>
```

### 5.2 Update your JavaScript
```javascript
import { httpsCallable } from "https://www.gstatic.com/firebasejs/9.22.0/firebase-functions.js";

// Get reference to Firebase Functions
const functions = getFunctions();

// Send verification code
async function sendVerificationCode() {
  const phone = document.getElementById("phone-input").value;
  
  if (!phone) {
    alert("Please enter a phone number");
    return;
  }

  try {
    const sendCode = httpsCallable(functions, "sendVerificationCode");
    const result = await sendCode({ phoneNumber: phone });
    
    console.log(result.data);
    
    // Show code input field
    document.getElementById("code-input").style.display = "block";
    document.getElementById("verify-btn").style.display = "block";
    
    document.getElementById("verification-status").innerText = 
      "✅ Code sent! Check your phone.";
      
  } catch (error) {
    console.error("Error sending code:", error);
    document.getElementById("verification-status").innerText = 
      "❌ Error: " + error.message;
  }
}

// Verify the code user entered
async function verifyPhoneCode() {
  const code = document.getElementById("code-input").value;
  
  if (!code) {
    alert("Please enter the verification code");
    return;
  }

  try {
    const verifyCode = httpsCallable(functions, "verifyCode");
    const result = await verifyCode({ code: code });
    
    console.log(result.data);
    
    document.getElementById("verification-status").innerText = 
      "✅ Phone verified successfully!";
      
    // Hide verification inputs
    document.getElementById("code-input").style.display = "none";
    document.getElementById("verify-btn").style.display = "none";
    
  } catch (error) {
    console.error("Error verifying code:", error);
    document.getElementById("verification-status").innerText = 
      "❌ Error: " + error.message;
  }
}
```

---

## Step 6: Send SMS on Important Events

### Example: Send Alert When Phishing Detected
```javascript
// In your scanInput() function
async function scanInput() {
  const input = document.getElementById("inputData").value.toLowerCase();
  
  // ... existing scanning logic ...
  
  if (detectedSignature) {
    // THREAT FOUND - Send SMS alert
    const user = window.firebaseAuth.currentUser;
    
    try {
      const sendAlert = httpsCallable(functions, "sendPhishingAlert");
      await sendAlert({
        threat: detectedSignature,
        url: input
      });
    } catch (error) {
      console.error("Error sending alert:", error);
    }
  }
}
```

### Firebase Function for Alerts
```javascript
// functions/index.js
exports.sendPhishingAlert = functions.https.onCall(
  async (data, context) => {
    if (!context.auth) {
      throw new functions.https.HttpsError(
        "unauthenticated",
        "User must be logged in"
      );
    }

    const { threat, url } = data;

    // Get user's phone from Firestore
    const userDoc = await admin
      .firestore()
      .collection("users")
      .doc(context.auth.uid)
      .get();

    const userPhone = userDoc.data().phone;

    try {
      await client.messages.create({
        body: `🚨 PhishGuard Alert! Phishing threat detected: "${threat}" in URL: ${url}`,
        from: twilioPhone,
        to: userPhone
      });

      return { success: true };
    } catch (error) {
      throw new functions.https.HttpsError("internal", error.message);
    }
  }
);
```

---

## Use Cases for PhishGuard

### 1. User Verification (During Signup)
```
User signup
  ↓
Send SMS code
  ↓
User enters code
  ↓
Phone verified ✅
```

### 2. Two-Factor Authentication (2FA)
```
User logs in
  ↓
Password correct
  ↓
Send SMS code
  ↓
User enters code
  ↓
Login successful ✅
```

### 3. Phishing Alert Notifications
```
User scans URL
  ↓
Phishing detected
  ↓
Send SMS alert immediately
  ↓
"⚠️ Phishing threat detected!"
```

### 4. Account Recovery
```
User clicks "Forgot Password"
  ↓
Send SMS code
  ↓
User verifies code
  ↓
Reset password
```

---

## Security Checklist

- [ ] Store credentials in environment variables (NOT in code)
- [ ] Never commit credentials to GitHub
- [ ] Use Firebase Functions (backend) for Twilio calls
- [ ] Validate phone numbers
- [ ] Rate limit SMS sending (prevent spam)
- [ ] Log all SMS activity
- [ ] Enable Twilio IP whitelist

---

## Environment Variables Setup

### .env File (for Node.js)
```
TWILIO_ACCOUNT_SID=AC1234567890abcdefghijklmnopqrst
TWILIO_AUTH_TOKEN=your-auth-token-here-secret
TWILIO_PHONE_NUMBER=+15551234567
```

### Access in Code
```javascript
const accountSid = process.env.TWILIO_ACCOUNT_SID;
const authToken = process.env.TWILIO_AUTH_TOKEN;
const twilioPhone = process.env.TWILIO_PHONE_NUMBER;
```

---

## Firebase Functions Environment Config

```bash
# Set environment variables
firebase functions:config:set twilio.account_sid="AC1234567890abcdefghijklmnopqrst"
firebase functions:config:set twilio.auth_token="your-auth-token-here-secret"
firebase functions:config:set twilio.phone_number="+15551234567"

# Access in functions
const accountSid = functions.config().twilio.account_sid;
```

---

## Pricing

### Twilio Pricing
- **SMS**: ~$0.0075 per message (varies by country)
- **Verification**: Free first 100 SMS/month, then paid
- **Free Trial**: $15 trial credit
- **No setup fees**

### Cost Estimates for PhishGuard
- 1000 SMS/month: ~$7.50
- 10,000 SMS/month: ~$75
- Enterprise volume: Custom pricing

---

## Troubleshooting

### Issue: "Invalid credentials"
- ✅ Check Account SID and Auth Token are correct
- ✅ Copy from Twilio Dashboard exactly
- ✅ No extra spaces

### Issue: "Phone number not valid"
- ✅ Include country code (+1 for USA)
- ✅ Remove spaces and dashes
- ✅ Use format: +1234567890

### Issue: "Message not received"
- ✅ Check phone number is correct
- ✅ Check SMS plan is active
- ✅ Check account has funds
- ✅ Try test number first

### Issue: "Cannot send more than X messages"
- ✅ Add rate limiting
- ✅ Implement cooldown periods
- ✅ Use Firestore to track attempts

---

## Complete Integration Example

### Full Signup Flow with Twilio
```javascript
// 1. User signs up with email/password
const userCredential = await createUserWithEmailAndPassword(
  auth, 
  email, 
  password
);

// 2. Save user to Firestore
await addDoc(collection(db, "users"), {
  uid: userCredential.user.uid,
  email: email,
  phone: null,
  phoneVerified: false,
  createdAt: new Date()
});

// 3. Send SMS verification code
await sendVerificationCode(phoneNumber);

// 4. User verifies code
await verifyPhoneCode(codeEntered);

// 5. Update Firestore with verified phone
await updateDoc(doc(db, "users", userId), {
  phone: phoneNumber,
  phoneVerified: true
});

// 6. User is fully registered ✅
```

---

## Next Steps

1. ✅ Create Twilio account
2. ✅ Get credentials (Account SID, Auth Token, Phone Number)
3. ✅ Set up Firebase Functions
4. ✅ Create verification code function
5. ✅ Add HTML form for phone verification
6. ✅ Call Firebase Functions from web app
7. ✅ Test with your own phone
8. ✅ Store verified phone in Firestore
9. ✅ Send alerts on phishing detection
10. ✅ Set up rate limiting

---

## Quick Reference

| What | Where | Example |
|------|-------|---------|
| Account SID | Twilio Console | AC1234567890abcde |
| Auth Token | Twilio Console | abc123xyz789... |
| Phone Number | Phone Numbers → Manage | +15551234567 |
| Send SMS | Firebase Function | httpsCallable() |
| Verify Code | Firebase Function | httpsCallable() |
| Store Phone | Firestore | users/{uid}/phone |

---

**You now know how to integrate Twilio! 🎉**

Questions? Let me know which part you need help with:
- Setting up Firebase Functions?
- Creating Twilio account?
- Testing SMS sending?
- Adding to your PhishGuard app?
