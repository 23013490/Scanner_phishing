# How to Copy Firebase Configuration

## Step-by-Step Guide to Get Your Firebase Config

### Step 1: Go to Firebase Console
- Open: https://console.firebase.google.com/
- Sign in with your Google account
- Click on your project (the one you created)

### Step 2: Find Your Web App Configuration

**Method 1: During App Registration (If you just created it)**
- After clicking "Register app", you'll see a code block like this:
```javascript
const firebaseConfig = {
  apiKey: "AIzaSyDxxxxxxxxxxxxxx",
  authDomain: "phishguard-xxxxx.firebaseapp.com",
  projectId: "phishguard-xxxxx",
  storageBucket: "phishguard-xxxxx.appspot.com",
  messagingSenderId: "123456789012",
  appId: "1:123456789012:web:abcdef1234567890"
};
```
- Look for a copy button (usually near the code) - click it to copy

**Method 2: From Project Settings (Anytime)**
1. In Firebase Console, click the **gear icon** (⚙️) in the top left
2. Select **"Project settings"**
3. Look for the **"Your apps"** section
4. Click on your web app name
5. Scroll down to find the Firebase SDK snippet
6. Copy the `firebaseConfig` object

### Step 3: What You'll See

The configuration looks like this:

```javascript
{
  apiKey: "AIzaSyDxxxxxxxxxxxxxx",           // 40-character API key
  authDomain: "projectname-xxxxx.firebaseapp.com",  // Your auth domain
  projectId: "projectname-xxxxx",            // Your project ID
  storageBucket: "projectname-xxxxx.appspot.com",   // Your storage bucket
  messagingSenderId: "123456789012",         // Messaging sender ID
  appId: "1:123456789012:web:abcdef1234567890"     // App ID
}
```

### Step 4: Copy Each Value

You have **two options**:

#### **Option A: Copy the Entire Block** (Easiest)
1. Select the entire `firebaseConfig` object (including the curly braces)
2. Right-click → Copy (or Ctrl+C)
3. You can paste this directly into your HTML files

#### **Option B: Copy Individual Values** (For reference)
1. **apiKey**: Copy from Firebase Console
2. **authDomain**: Copy from Firebase Console
3. **projectId**: Copy from Firebase Console
4. **storageBucket**: Copy from Firebase Console
5. **messagingSenderId**: Copy from Firebase Console
6. **appId**: Copy from Firebase Console

### Step 5: Paste into Your HTML Files

#### **For index.html:**
1. Open `index.html` in your code editor (VS Code)
2. Find lines 15-23 (search for "firebaseConfig")
3. You'll see:
```javascript
const firebaseConfig = {
  apiKey: "your-api-key",
  authDomain: "your-project.firebaseapp.com",
  projectId: "your-project-id",
  storageBucket: "your-project.appspot.com",
  messagingSenderId: "your-sender-id",
  appId: "your-app-id"
};
```

4. Replace the placeholder values with your actual values:
```javascript
const firebaseConfig = {
  apiKey: "AIzaSyDxxxxxxxxxxxxxx",              // Paste here
  authDomain: "phishguard-abc123.firebaseapp.com",  // Paste here
  projectId: "phishguard-abc123",               // Paste here
  storageBucket: "phishguard-abc123.appspot.com",   // Paste here
  messagingSenderId: "123456789012",            // Paste here
  appId: "1:123456789012:web:abc123def456"     // Paste here
};
```

5. **Save the file** (Ctrl+S)

#### **For profile.html:**
1. Open `profile.html`
2. Find the `firebaseConfig` object (around line 10-20)
3. Paste the same configuration
4. Save the file

#### **For subscription.html:**
1. Open `subscription.html`
2. Find the `firebaseConfig` object (around line 10-20)
3. Paste the same configuration
4. Save the file

## Quick Visual Example

**BEFORE (Placeholder):**
```javascript
const firebaseConfig = {
  apiKey: "your-api-key",                    ❌ Placeholder
  authDomain: "your-project.firebaseapp.com", ❌ Placeholder
  projectId: "your-project-id",              ❌ Placeholder
  storageBucket: "your-project.appspot.com", ❌ Placeholder
  messagingSenderId: "your-sender-id",       ❌ Placeholder
  appId: "your-app-id"                       ❌ Placeholder
};
```

**AFTER (Real Values):**
```javascript
const firebaseConfig = {
  apiKey: "AIzaSyC_JG5VN3-xXx8vXxXxXxXxXxXxXxXxXxX",        ✅ Real
  authDomain: "phishguard-12345.firebaseapp.com",           ✅ Real
  projectId: "phishguard-12345",                            ✅ Real
  storageBucket: "phishguard-12345.appspot.com",            ✅ Real
  messagingSenderId: "123456789012",                        ✅ Real
  appId: "1:123456789012:web:abcdef1234567890"             ✅ Real
};
```

## Verification Checklist

After pasting your configuration:

- [ ] All 6 values are filled in (not "your-api-key" etc.)
- [ ] No extra spaces at the beginning or end
- [ ] Commas are present between all fields except the last one
- [ ] The configuration is in all three files (index.html, profile.html, subscription.html)
- [ ] Files are saved

## Testing

1. Open `index.html` in your browser
2. You should see the login form (not an error)
3. Try logging in with your test credentials:
   - Email: test@example.com
   - Password: Test@1234 (from Firebase Console)

If you see an error like "Firebase is not defined", the configuration may have an issue.

## Common Mistakes to Avoid

❌ **Don't do this:**
- Copy only part of the config
- Include extra quotes or brackets
- Paste in the wrong place
- Forget to save after pasting
- Use old/copied values from someone else

✅ **Do this:**
- Copy the entire `firebaseConfig` block
- Paste it exactly as shown in Firebase Console
- Replace all placeholder values
- Save all three HTML files
- Test immediately after

## Getting Help

If you're stuck:
1. Compare your config with the Firebase Console exactly
2. Check for typos in the values
3. Make sure you copied the entire value (some are long)
4. Clear your browser cache and reload
5. Check the browser console for error messages (F12 → Console tab)

---

**Need help finding your Firebase config? Let me know!**
