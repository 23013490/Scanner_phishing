# Firebase Setup Guide for PhishGuard Scanner

## Step 1: Create a Firebase Project

1. Go to **Firebase Console**: https://console.firebase.google.com/
2. Click **"Create a project"** or **"Add project"**
3. Enter your project name (e.g., "PhishGuard Scanner")
4. Accept the terms and click **"Continue"**
5. Choose whether to enable Google Analytics (optional) - click **"Continue"**
6. Click **"Create project"** and wait for it to initialize

## Step 2: Register Your Web App

1. In your Firebase project, click the **Web icon** (</>) to add a web app
2. Enter your app name (e.g., "PhishGuard Web")
3. Check **"Also set up Firebase Hosting for this app"** (optional)
4. Click **"Register app"**
5. You'll see a code snippet with your Firebase configuration

## Step 3: Copy Your Firebase Configuration

You'll see something like this:
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

**Important:** Copy this entire configuration - you'll need it for Step 6.

## Step 4: Enable Email/Password Authentication

1. In Firebase Console, go to **Authentication** (left sidebar)
2. Click on the **"Sign-in method"** tab
3. Click **"Email/Password"**
4. Toggle **"Enable"** to turn it ON
5. Click **"Save"**

## Step 5: Create Test Users

1. In Firebase Console, stay in **Authentication**
2. Click the **"Users"** tab
3. Click **"Add user"** (top right)
4. Enter:
   - **Email**: test@example.com
   - **Password**: Test@1234 (use a strong password)
5. Click **"Add user"**
6. Create a few more test users with different emails if needed

**Example test accounts:**
- Email: demo@phishguard.com | Password: Demo@1234
- Email: user@phishguard.com | Password: User@1234
- Email: test@phishguard.com | Password: Test@1234

## Step 6: Update Your Application

1. Open `index.html` in your code editor
2. Find the Firebase configuration section (around lines 15-23):
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

3. Replace it with your actual Firebase configuration from Step 3
4. Save the file

## Step 7: Update subscription.html

1. Open `subscription.html`
2. Find the Firebase configuration section (around lines 10-20)
3. Replace it with your actual Firebase configuration
4. Save the file

## Step 8: Update profile.html

1. Open `profile.html`
2. Find the Firebase configuration section (around lines 10-20)
3. Replace it with your actual Firebase configuration
4. Save the file

## Step 9: Test Your Application

1. Open `index.html` in your web browser
2. You should see the login form
3. Try logging in with one of your test accounts:
   - Email: test@example.com
   - Password: Test@1234
4. If successful, you'll see the PhishGuard Scanner interface
5. You can now access:
   - **Scanner** - Check for phishing threats
   - **Dashboard** - View statistics
   - **Profile** - See your account info (click Profile button)
   - **Subscribe** - View pricing plans (click Subscribe button)
   - **Logout** - Sign out from any page

## Troubleshooting

### "Invalid email or password" error
- Double-check your test user credentials in Firebase Console
- Ensure Email/Password authentication is enabled
- Verify the Firebase config is correctly copied

### "Failed to initialize Firebase"
- Check that all fields in the Firebase config are filled correctly
- Ensure there are no extra spaces or typos
- Verify your internet connection

### Changes not reflecting
- Clear your browser cache (Ctrl+Shift+Delete)
- Close and reopen the browser
- Try a different browser or incognito mode

## Firebase Security Tips (For Production)

1. **Enable reCAPTCHA**: In Authentication > Sign-in method > Email/Password > Enable CAPTCHA
2. **Restrict API Key**: In Firebase Console > Settings > Service Accounts, restrict your API key to only web applications
3. **Set Security Rules**: In Firestore/Realtime Database, configure proper security rules
4. **Enable two-factor authentication** for your Firebase account
5. **Don't share** your Firebase configuration in public repositories

## Additional Resources

- Firebase Documentation: https://firebase.google.com/docs
- Firebase Authentication Guide: https://firebase.google.com/docs/auth
- Firebase Console: https://console.firebase.google.com/

## Next Steps

After setting up Firebase:
1. Add more features like password reset
2. Implement user profiles in Firestore database
3. Add email verification
4. Set up cloud functions for advanced features
5. Deploy to Firebase Hosting or your own server

---

**Questions?** Refer to the Firebase documentation or contact Firebase support.
