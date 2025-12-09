<!DOCTYPE html>n Explained - Simple Guide
<html lang="en">
  <head>is Authentication?
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />are.
    <title>Signature Scanner</title>
    <!-- Firebase SDK -->
    <script type="module">When you go to a bank, you show your ID card. The bank worker checks it to make sure you're really you, not someone pretending to be you.
      import { initializeApp } from "https://www.gstatic.com/firebasejs/9.22.0/firebase-app.js";these credentials to make sure you're the real owner of that email.
      import {
        getAuth,
        signInWithEmailAndPassword,
        signOut,ation Works in Your PhishGuard App
      } from "https://www.gstatic.com/firebasejs/9.22.0/firebase-auth.js";
### The Simple Flow (3 Steps)
      // Firebase configuration (replace with your own config)
      // Import the functions you need from the SDKs you need
      import { initializeApp } from "firebase/app";
      // TODO: Add SDKs for Firebase products that you want to use
      // https://firebase.google.com/docs/web/setup#available-libraries
   ↓
      // Your web app's Firebase configuration
      const firebaseConfig = {
        apiKey: "AIzaSyAoMgJagqLxWhJmco2KHksA5A3pWSOKacg",
        authDomain: "signature-scanner-9e03f.firebaseapp.com",
        projectId: "signature-scanner-9e03f",
        storageBucket: "signature-scanner-9e03f.firebasestorage.app",
        messagingSenderId: "510792708222",
        appId: "1:510792708222:web:da3e4295830f222ebca82f",
      };
  Email: test@example.com
      // Initialize Firebase
      const app = initializeApp(firebaseConfig);
      const auth = getAuth(app);

      window.firebaseAuth = auth;ntials**
    </script>nds the email and password to Firebase servers
    <style>searches its database for a user with that email
      :root {ecks if the password matches what they have stored
        --primary: #2563eb;uccess" or "❌ Failed"
        --primary-dark: #1d4ed8;
        --primary-light: #3b82f6;jected**
        --danger: #dc2626;e PhishGuard Scanner page
        --success: #16a34a; message "Invalid email or password"
        --warning: #f59e0b;
        --bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --card: #ffffff;
        --shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        --shadow-hover: 0 15px 35px rgba(0, 0, 0, 0.15);
      }ore Authentication (Not Logged In)
```
      * {                        Firebase Server
        box-sizing: border-box;       ↓
        font-family: "Segoe UI", system-ui, sans-serif;ls
      }                               ↓
User enters email & password
      body {
        background: var(--bg);
        display: flex;
        justify-content: center;
        align-items: center;ogging In)
        min-height: 100vh;
        margin: 0;               Firebase Server
        padding: 20px;                ↓
        position: relative;---→ Check if email exists
      }                             ↓
                            Check if password matches
      body::before {                ↓
        content: "";        Create session/token
        position: absolute;         ↓
        top: 0;     ←-------- Send "OK" message back
        left: 0;
        right: 0;ion info
        bottom: 0;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        z-index: -1;
      }

      .scanner-container {Logged In)
        background: var(--card);
        padding: 2.5rem;         Firebase Server
        border-radius: 16px;          ↓
        box-shadow: var(--shadow);er verified
        width: 100%;
        max-width: 550px;
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.2);
      }

      .scanner-container:hover {
        box-shadow: var(--shadow-hover);
        transform: translateY(-2px);
      }
### Part 1: HTML - The Login Form
      h1 {
        margin-top: 0;
        color: #1e293b;d="email" placeholder="Email" required />
        font-size: 1.8rem;d="password" placeholder="Password" required />
        font-weight: 700;ogin</button>
        text-align: center;
        margin-bottom: 0.5rem;
      }
This creates the form where users enter their credentials.
      .description {
        color: #64748b;- The Authentication Logic
        font-size: 1rem;
        margin-bottom: 2rem;in-form").addEventListener("submit", async function(event) {
        text-align: center;// Stop form from refreshing page
        line-height: 1.5;
      } email = document.getElementById("email").value;  // Get email
  const password = document.getElementById("password").value;  // Get password
      textarea {
        width: 100%;
        height: 120px;s to Firebase
        padding: 16px;al = await signInWithEmailAndPassword(
        border: 2px solid #e2e8f0; Firebase auth
        border-radius: 12px;// User's email
        resize: vertical;   // User's password
        margin-bottom: 1.5rem;
        font-family: monospace;
        font-size: 0.9rem;
        transition: border-color 0.3s ease;r").style.display = "none";  // Hide login
        background: #f8fafc;"scanner-container").style.display = "block";  // Show scanner
      }
  } catch (error) {
      textarea:focus {
        outline: none;tById("error-message").textContent = "Invalid email or password.";
        border-color: var(--primary);ror").style.display = "block";  // Show error
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
      }
```
      input {
        width: 100%;plain English:**
        padding: 16px;bmit the form
        border: 2px solid #e2e8f0; entered
        border-radius: 12px;check
        margin-bottom: 1.5rem;er page
        font-family: inherit;age
        font-size: 1rem;
        transition: border-color 0.3s ease;
        background: #f8fafc;
      }Authentication Concepts

      input:focus {(What you log in with)
        outline: none;ue identifier (like a username)
        border-color: var(--primary);code (only you should know it)
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
      }
Email: user@example.com
      form {Secret@123
        margin-bottom: 1.5rem;
      }
### 2. Authentication vs Authorization
      button {
        background: linear-gradient( say you are?"
          135deg,g your password to login
          var(--primary) 0%, this password
          var(--primary-light) 100%
        );ation**: "What are you allowed to do?"
        color: white;cess the scanner? Can you upload files?
        border: none;ro user you can scan 100x per day
        padding: 16px 24px;
        border-radius: 12px;
        cursor: pointer;
        font-weight: 600;ully, Firebase creates a **token** (like a digital ID card):
        font-size: 1rem;d in
        width: 100%;ry request to prove who you are
        transition: all 0.3s ease;stroyed
        box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
      }
Login Successful
      button:hover {
        background: linear-gradient(9..."
          135deg,
          var(--primary-dark) 0%,
          var(--primary) 100%
        );on uses this token to prove you're authenticated
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
      }
Token is deleted
      button:active {
        transform: translateY(0);
      }

      .result-box {gy
        margin-top: 2rem;
        padding: 1.5rem;t with Authentication
        border-radius: 12px;
        display: none; You arrive at the restaurant
        animation: fadeIn 0.5s ease;servation (your credentials)
      }u provide your name and confirmation number
   - Hostess verifies it matches their list
      @keyframes fadeIn {irmed, welcome!"
        from {
          opacity: 0;u're now authenticated
          transform: translateY(10px);(like a token)
        }use this table number for the rest of the visit
   - Waiters use it to know where to bring your food
        to {
          opacity: 1; What you can do
          transform: translateY(0);our permission level)
        }cannot access the kitchen (not authorized)
      }u cannot sit at a reserved table (not authorized)

      .result-box.danger {
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        border: 2px solid #fecaca;le now
        color: var(--danger);
      }

      .result-box.safe {hGuard App
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        border: 2px solid #bbf7d0;
        color: var(--success);
      }ens index.html
  ↓
      .signature-match {
        font-weight: bold;
        display: block;
        margin-top: 0.5rem;
        font-size: 0.9rem;yet
        background: rgba(0, 0, 0, 0.05);
        padding: 8px 12px;
        border-radius: 6px;dentials
        border-left: 4px solid currentColor;
      }demo@phishguard.com
Password: Demo@1234
      .nav-buttons {
        display: flex;
        gap: 15px;
        margin-bottom: 2rem;ication
        justify-content: center;
      }e checks:
✓ Does user "demo@phishguard.com" exist? YES
      .nav-buttons button {4" correct? YES
        flex: 1;
        background: linear-gradient(
          135deg,
          var(--primary) 0%,en
          var(--primary-light) 100%
        );
        color: white;nse
        border: none;
        padding: 12px 20px;
        border-radius: 10px;
        cursor: pointer;shown
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
        max-width: 150px;
      }subscription plans
```
      .nav-buttons button:hover {
        background: linear-gradient(
          135deg,
          var(--primary-dark) 0%,
          var(--primary) 100%
        );ndow.firebaseAuth) is called
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
      }
Login form appears again
      .nav-buttons button:active {
        transform: translateY(0);
      }

      /* Dashboard specific styles */
      #dashboard-content h3 {
        color: #1e293b;User Data
        font-size: 1.4rem;
        margin-bottom: 1.5rem;on securely:
        text-align: center;
      }
Firebase Database (Secure)
      #dashboard-content ul {
        list-style: none;om
        padding: 0;demo@phishguard.com
        margin: 0;rd: $2a$10$encrypted... (NOT plain text!)
      } └── Created: 2025-12-09
│   │
      #dashboard-content li {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        margin-bottom: 12px;$encrypted...
        padding: 16px 20px;2-08
        border-radius: 10px;
        display: flex;
        justify-content: space-between; not stored as plain text.
        align-items: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.5);
      }
### 1. Password Encryption
      #dashboard-content li:hover {ash (unreadable format):
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      }ed in Firebase: $2a$10$8d9f7e8c7b6a5d4c3b2a...
```
      #dashboard-content li strong {
        color: var(--primary);
        font-weight: 600;ection)
      }u send credentials, they're sent through an encrypted tunnel:
```
      #dashboard-content li span {se
        background: var(--primary);
        color: white;
        padding: 4px 12px;────→↓
        border-radius: 20px;D        Demo@1234
        font-size: 0.8rem;            (safe)
        font-weight: 600;
      }
### 3. Session Tokens
      /* Navigation header styles */me, you get a token:
      .nav-header {
        display: flex;l + password → Get token
        justify-content: space-between; → Verified
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid #e2e8f0;
      }on Authentication Scenarios

      .nav-header h2 {t Credentials
        margin: 0;
        color: #1e293b;om / Demo@1234
        font-size: 1.2rem;
      } Login successful, show scanner
```
      .header-buttons {
        display: flex;Password
        gap: 10px;
      }emo@phishguard.com / WrongPassword
Firebase: "Email found, but password doesn't match ❌"
      .header-buttons button {ail or password"
        padding: 8px 16px;
        font-size: 0.85rem;
        width: auto;l Not Found
        max-width: 120px;
      }nknown@phishguard.com / Demo@1234
Firebase: "Email not found ❌"
      .logout-btn {"Invalid email or password" (same message for security)
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
      }
### Scenario 4: Empty Fields
      .logout-btn:hover {
        background: linear-gradient(135deg, #b91c1c 0%, #991b1b 100%);
      }e: "Required field missing ❌"
Result: Browser shows "Please fill in email" (HTML validation)
      .user-info {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        border: 2px solid #bbf7d0;
        padding: 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
      }requisites for Authentication
1. ✅ Firebase project created
      .user-info h3 {uthentication enabled in Firebase
        margin: 0 0 1rem 0;Firebase Console
        color: var(--success);pied to your HTML files
        font-size: 1.1rem;to reach Firebase servers)
      }
### How to Test
      .user-info-item { browser
        margin: 0.75rem 0;om Firebase Console
        color: #1e293b;t@example.com` / password: `Test@1234`
      }k Login
5. If correct → PhishGuard Scanner shows
      .user-info-item strong {pears
        color: var(--success);
      }t Happens After Login
- Form is hidden
      /* Subscription styles */
      .subscription-container {
        max-width: 900px;o go to other pages
      }an click "Logout" to return to login

      .subscription-plans {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
      }───────────────────────────────────────────────────┐
│                   START: User Opens App                 │
      .plan-card {───┬────────────────────────────────────┘
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border: 2px solid #cbd5e1;───┐
        border-radius: 12px;         │
        padding: 1.5rem;sword Input) │
        text-align: center;──────────┘
        transition: all 0.3s ease;
        position: relative;──────────┐
      }  │  User Enters Credentials  │
         │  Clicks "Login" Button    │
      .plan-card:hover {─────────────┘
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        border-color: var(--primary);│
      }  │ (Email + Password)        │
         └────────────┬──────────────┘
      .plan-card.featured {
        border: 2px solid var(--primary);
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        transform: scale(1.05);      │
      }  │ - Check password matches  │
         └──────┬──────────┬─────────┘
      .plan-name {         ↓
        font-size: 1.3rem;iled
        font-weight: 700; ↓
        color: #1e293b;┌────────────────────┐
        margin-bottom: 0.5rem; Error        │
      }  Token      │  │  "Invalid email    │
      │  ↓          │  │   or password"     │
      .plan-price { │  │  ↓                 │
        font-size: 2rem; Stay on Login Form │
        font-weight: 700;───────────────────┘
        color: var(--primary);  ↓
        margin-bottom: 0.25rem; Retries
      } Page        │
      │ ↓           │
      .plan-period {│
        font-size: 0.85rem;
        color: #64748b;
        margin-bottom: 1rem;
      }
---
      .plan-features {
        list-style: none;
        padding: 0;
        margin: 1.5rem 0;
        text-align: left;
      }ng email and password
- ✅ Firebase checks credentials
      .plan-features li {rrect
        padding: 0.5rem 0;
        color: #475569;
        font-size: 0.9rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
      }ds credentials to Firebase
- ✅ Firebase responds with success/failure
      .plan-features li:before {ge
        content: "✓ ";ytime
        color: var(--success);
        font-weight: 700;**
        margin-right: 0.5rem;fe)
      }nication is encrypted (safe)
- Tokens replace passwords after login (efficient)
      .plan-button {ng email/password (security)
        background: linear-gradient((reliable)
          135deg,
          var(--primary) 0%,
          var(--primary-light) 100%
        );ns to Test Your Understanding
        color: white;
        border: none;rence between authentication and authorization?**
        padding: 12px 24px;g who you are
        border-radius: 8px;u're allowed to do
        cursor: pointer;
        font-weight: 600;verification happen?**
        font-size: 0.9rem; in your browser)
        width: 100%;
        margin-top: 1rem;enter wrong credentials?**
        transition: all 0.3s ease; form stays visible
      }
4. **What is a session token?**
      .plan-button:hover {roves you're logged in (instead of sending password every time)
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
      }curity, reliability, and we don't have to manage user databases ourselves

      .plan-card.featured .plan-button {
        background: linear-gradient(
          135deg,and authentication! 🎉**
          var(--primary-dark) 0%,
          var(--primary) 100%me to explain further?
        );      }      /* Footer styles */      footer {        text-align: center;        padding: 1.5rem;        color: #64748b;        font-size: 0.85rem;        border-top: 1px solid rgba(255, 255, 255, 0.1);        margin-top: 2rem;      }      .login-footer {        position: fixed;        bottom: 0;        left: 0;        right: 0;        background: rgba(0, 0, 0, 0.2);        backdrop-filter: blur(10px);      }      .content-wrapper {        padding-bottom: 100px;      }    </style>  </head>  <body>    <div id="login-container" class="scanner-container">      <h1>Login to PhishGuard Scanner</h1>      <p class="description">        Please enter your credentials to access the scanner.      </p>      <form id="login-form">        <input type="email" id="email" placeholder="Email" required />        <input type="password" id="password" placeholder="Password" required />        <button type="submit">Login</button>      </form>      <div id="login-error" class="result-box danger" style="display: none">        <span id="error-message">Invalid email or password.</span>      </div>    </div>    <div id="scanner-container" class="scanner-container" style="display: none">      <div class="nav-header">        <h2>PhishGuard Scanner</h2>        <div class="header-buttons">          <button onclick="window.location.href='profile.html'">Profile</button>          <button onclick="window.location.href='subscription.html'">            Subscribe          </button>          <button onclick="logoutUser()" class="logout-btn">Logout</button>        </div>      </div>      <p class="description">        Enter a URL or email text below. This tool checks against a local        database of known phishing signatures.      </p>      <div class="nav-buttons">        <button onclick="showScanner()">Scanner</button>        <button onclick="showDashboard()">Dashboard</button>      </div>      <textarea        id="inputData"        placeholder="Paste URL or text here (e.g., http://secure-login-update.com)..."      ></textarea>      <button onclick="scanInput()">Scan Now</button>      <div id="result" class="result-box">        <h3 id="resultTitle" style="margin: 0 0 0.5rem 0"></h3>        <span id="resultMessage"></span>      </div>    </div>    <div      id="user-profile-container"      class="scanner-container"      style="display: none"    ></div>    <div      id="subscription-container"      class="scanner-container subscription-container"      style="display: none"    ></div>    <div      id="dashboard-container"      class="scanner-container"      style="display: none"    >      <div class="nav-header">        <h2>PhishGuard Dashboard</h2>        <div class="header-buttons">          <button onclick="showScanner()">Scanner</button>          <button onclick="window.location.href='profile.html'">Profile</button>          <button onclick="logoutUser()" class="logout-btn">Logout</button>        </div>      </div>      <p class="description">View the most common phishing attacks detected.</p>      <div id="dashboard-content"></div>    </div>    <footer>      <p>        &copy; 2025 PhishGuard Scanner. All rights reserved. |        <a href="#" style="color: #64748b; text-decoration: none"          >Privacy Policy</a        >        |        <a href="#" style="color: #64748b; text-decoration: none"          >Terms of Service</a        >      </p>    </footer>    <script>      // Firebase login functionality      document        .getElementById("login-form")        .addEventListener("submit", async function (event) {          event.preventDefault();          const email = document.getElementById("email").value.trim();          const password = document.getElementById("password").value;          try {            const userCredential = await signInWithEmailAndPassword(              window.firebaseAuth,              email,              password            );            // Login successful            document.getElementById("login-container").style.display = "none";            updateUserProfile(userCredential.user);            showScanner();            document.getElementById("login-error").style.display = "none";          } catch (error) {            // Login failed            document.getElementById("error-message").textContent =              "Invalid email or password.";            document.getElementById("login-error").style.display = "block";          }        });      function updateUserProfile(user) {        document.getElementById("user-email").textContent = user.email || "N/A";        document.getElementById("user-id").textContent = user.uid || "N/A";        document.getElementById("user-joined").textContent = new Date(          user.metadata.creationTime        ).toLocaleDateString();      }      function showScanner() {        document.getElementById("scanner-container").style.display = "block";        document.getElementById("dashboard-container").style.display = "none";      }      function showDashboard() {        document.getElementById("scanner-container").style.display = "none";        document.getElementById("dashboard-container").style.display = "block";        displayDashboard();      }      async function logoutUser() {        try {          await signOut(window.firebaseAuth);          // Logout successful          document.getElementById("login-container").style.display = "block";          document.getElementById("scanner-container").style.display = "none";          document.getElementById("dashboard-container").style.display = "none";          // Clear form          document.getElementById("login-form").reset();          document.getElementById("login-error").style.display = "none";        } catch (error) {          alert("Error logging out: " + error.message);        }      }      function displayDashboard() {        const content = document.getElementById("dashboard-content");        content.innerHTML = "<h3>Top Phishing Attacks</h3><ul>";        const sorted = Object.entries(attackFrequencies).sort(          (a, b) => b[1] - a[1]        );        sorted.forEach(([sig, freq]) => {          content.innerHTML += `<li><strong>${sig}</strong><span>${freq}</span></li>`;        });        content.innerHTML += "</ul>";      }      function updateUserProfile(user) {        // User profile is now on separate page      }      function selectPlan(planName) {        // Plan selection is now on separate page      }      function showUserProfile() {        window.location.href = "profile.html";      }      function showSubscription() {        window.location.href = "subscription.html";      } // 1. The "Database" of Signatures      // In a real app, this would be an API call to a massive threat intel feed.      const signatureDatabase = [        "secure-update-login",        "verify-account-now",        "free-iphone-winner",        "paypal-suspicious-activity",        "bank-of-america-alert",        "ngrok.io", // Often used for tunneling phishing sites        "000webhostapp", // Free hosting often abused        "bit.ly/suspicious",      ];      const attackFrequencies = {        "secure-update-login": 45,        "verify-account-now": 32,        "free-iphone-winner": 28,        "paypal-suspicious-activity": 50,        "bank-of-america-alert": 38,        "ngrok.io": 60,        "000webhostapp": 42,        "bit.ly/suspicious": 35,      };      function scanInput() {        const input = document.getElementById("inputData").value.toLowerCase();        const resultBox = document.getElementById("result");        const title = document.getElementById("resultTitle");        const message = document.getElementById("resultMessage");        if (!input) {          alert("Please enter some text to scan.");          return;        }        // 2. The Scanning Logic (Signature Matching)        let detectedSignature = null;        // We loop through our database to see if the input includes any bad strings        for (let sig of signatureDatabase) {          if (input.includes(sig)) {            detectedSignature = sig;            break; // Stop looking once we find a threat          }        }        // 3. Display Results        resultBox.style.display = "block";        resultBox.className = "result-box"; // Reset classes        if (detectedSignature) {          // THREAT FOUND          resultBox.classList.add("danger");          title.innerText = "⚠️ Phishing Threat Detected";          message.innerHTML = `This input matches a known phishing signature.<br>                                 <span class="signature-match">Match found: "${detectedSignature}"</span>`;        } else {          // CLEAN          resultBox.classList.add("safe");          title.innerText = "✅ No Signatures Found";          message.innerText =            "The input does not match any known signatures in our local database.";        }      }      // Add new user to Firestore (example code)      await addDoc(collection(db, "users"), {        email: "new@example.com",
        name: "New User",
        plan: "Free",
      });
    </script>
  </body>
</html>
