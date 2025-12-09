# How to Create a Table (Database) Using Firebase

## What is a Firebase Table?

A **Firebase Table** (also called a **Collection** in Firestore) is like a spreadsheet where you store data.

**Real-world example:**
- Excel spreadsheet with rows and columns
- Each row is a record (document)
- Each column is a field (property)

---

## Firebase Database Options

Firebase has **2 main database options**:

### Option 1: Firestore (Recommended) ⭐
- Modern, structured database
- Uses Collections and Documents
- Best for most applications
- **We'll use this one**

### Option 2: Realtime Database
- Simple JSON database
- Good for real-time updates
- Older technology

---

## Step 1: Enable Firestore in Firebase Console

### 1.1 Go to Firebase Console
- Open: https://console.firebase.google.com/
- Click on your project

### 1.2 Enable Firestore
1. In the left sidebar, click **"Firestore Database"**
2. Click **"Create database"**
3. Choose:
   - **Location**: Select closest region (e.g., us-east1)
   - **Security Rules**: Choose **"Start in test mode"** (for development)
4. Click **"Create"**
5. Wait for it to initialize (1-2 minutes)

---

## Step 2: Create a Collection (Table)

### What is a Collection?
A Collection is like a table. Each item in the collection is a Document.

```
Collection: "users" (like a table)
├── Document 1
│   ├── name: "John"
│   ├── email: "john@example.com"
│   └── plan: "Pro"
│
├── Document 2
│   ├── name: "Sarah"
│   ├── email: "sarah@example.com"
│   └── plan: "Free"
```

### 2.1 Create Collection Steps
1. In Firestore, click **"+ Create collection"**
2. Enter collection name: `users`
3. Click **"Next"**
4. Click **"Auto ID"** to generate automatic ID
5. Add first document with fields:
   - `name`: John Doe
   - `email`: john@example.com
   - `plan`: Pro
6. Click **"Save"**

### 2.2 What You'll See
```
Firestore Database
├── users (Collection)
│   └── abc123def456 (Document ID - auto-generated)
│       ├── name: John Doe
│       ├── email: john@example.com
│       └── plan: Pro
```

---

## Common Table Examples for PhishGuard

### Example 1: Users Collection
```
Collection: "users"

Document Fields:
- uid: auto-generated ID
- email: user@example.com
- displayName: John Doe
- plan: Free/Pro/Enterprise
- createdAt: 2025-12-09
- scansUsed: 5
```

### Example 2: Scan History Collection
```
Collection: "scanHistory"

Document Fields:
- scanId: auto-generated
- userId: reference to user
- urlScanned: http://example.com
- result: phishing/safe
- timestamp: 2025-12-09T10:30:00
- matchedSignature: secure-update-login
```

### Example 3: Plans Collection
```
Collection: "plans"

Document Fields:
- planId: free/pro/enterprise
- name: Pro
- price: 9.99
- currency: USD
- features: [unlimited scans, api access, priority support]
- maxScans: unlimited
```

---

## Step 3: Add Data to Your Collection

### Method 1: Manual (via Firebase Console)
1. Click your collection (e.g., "users")
2. Click **"+ Add document"**
3. Enter fields and values:
   - Field: `email` | Value: `test@example.com`
   - Field: `name` | Value: `Test User`
   - Field: `plan` | Value: `Free`
4. Click **"Save"**

### Method 2: Programmatically (via JavaScript)

Here's how to add data from your web app:

```javascript
// Import Firestore functions
import { 
  getFirestore, 
  collection, 
  addDoc,
  query,
  where,
  getDocs
} from "https://www.gstatic.com/firebasejs/9.22.0/firebase-firestore.js";

// Get Firestore database
const db = getFirestore(app);

// Add a new document
async function addUser(email, name, plan) {
  try {
    const docRef = await addDoc(collection(db, "users"), {
      email: email,
      name: name,
      plan: plan,
      createdAt: new Date()
    });
    console.log("User added with ID: ", docRef.id);
  } catch (error) {
    console.error("Error adding user: ", error);
  }
}

// Call the function
addUser("john@example.com", "John Doe", "Pro");
```

---

## Step 4: Read Data from Your Collection

### Read All Documents
```javascript
async function getAllUsers() {
  try {
    const querySnapshot = await getDocs(collection(db, "users"));
    
    querySnapshot.forEach((doc) => {
      console.log(doc.id, " => ", doc.data());
      // Output: abc123 => { email: john@example.com, name: John Doe, plan: Pro }
    });
  } catch (error) {
    console.error("Error getting users: ", error);
  }
}

// Call it
getAllUsers();
```

### Read Specific Document
```javascript
import { getDoc, doc } from "https://www.gstatic.com/firebasejs/9.22.0/firebase-firestore.js";

async function getUser(userId) {
  try {
    const docSnap = await getDoc(doc(db, "users", userId));
    
    if (docSnap.exists()) {
      console.log("User data:", docSnap.data());
    } else {
      console.log("No user found!");
    }
  } catch (error) {
    console.error("Error getting user: ", error);
  }
}

// Call it
getUser("abc123def456");
```

### Search with Query
```javascript
async function findUserByEmail(email) {
  try {
    const q = query(collection(db, "users"), where("email", "==", email));
    const querySnapshot = await getDocs(q);
    
    querySnapshot.forEach((doc) => {
      console.log("Found user:", doc.data());
    });
  } catch (error) {
    console.error("Error searching: ", error);
  }
}

// Call it
findUserByEmail("john@example.com");
```

---

## Step 5: Update Data

```javascript
import { updateDoc, doc } from "https://www.gstatic.com/firebasejs/9.22.0/firebase-firestore.js";

async function updateUserPlan(userId, newPlan) {
  try {
    await updateDoc(doc(db, "users", userId), {
      plan: newPlan,
      updatedAt: new Date()
    });
    console.log("User updated!");
  } catch (error) {
    console.error("Error updating: ", error);
  }
}

// Call it
updateUserPlan("abc123def456", "Enterprise");
```

---

## Step 6: Delete Data

```javascript
import { deleteDoc, doc } from "https://www.gstatic.com/firebasejs/9.22.0/firebase-firestore.js";

async function deleteUser(userId) {
  try {
    await deleteDoc(doc(db, "users", userId));
    console.log("User deleted!");
  } catch (error) {
    console.error("Error deleting: ", error);
  }
}

// Call it
deleteUser("abc123def456");
```

---

## Complete Example: User Registration with Firestore

Here's how to save user data when they sign up:

```javascript
import { getFirestore, collection, addDoc } from "https://www.gstatic.com/firebasejs/9.22.0/firebase-firestore.js";

const db = getFirestore(app);

// When user signs up, save their info to Firestore
async function saveUserToFirestore(user, plan = "Free") {
  try {
    await addDoc(collection(db, "users"), {
      uid: user.uid,                    // Firebase Auth UID
      email: user.email,                // User's email
      displayName: user.displayName || "User",
      plan: plan,                       // Subscription plan
      createdAt: new Date(),            // Signup date
      scansUsed: 0,                     // Track scan usage
      lastLogin: new Date()             // Last login time
    });
    console.log("User saved to Firestore!");
  } catch (error) {
    console.error("Error saving user: ", error);
  }
}

// After user signs in:
import { signInWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/9.22.0/firebase-auth.js";

const userCredential = await signInWithEmailAndPassword(auth, email, password);
const user = userCredential.user;

// Save to Firestore
saveUserToFirestore(user);
```

---

## Display Table Data on Your Web Page

### HTML to Display Users Table
```html
<table id="usersTable">
  <thead>
    <tr>
      <th>Email</th>
      <th>Name</th>
      <th>Plan</th>
      <th>Joined</th>
    </tr>
  </thead>
  <tbody id="tableBody">
    <!-- JavaScript will fill this -->
  </tbody>
</table>
```

### JavaScript to Populate Table
```javascript
async function displayUsersTable() {
  try {
    const querySnapshot = await getDocs(collection(db, "users"));
    const tableBody = document.getElementById("tableBody");
    tableBody.innerHTML = ""; // Clear existing rows
    
    querySnapshot.forEach((doc) => {
      const user = doc.data();
      const row = `
        <tr>
          <td>${user.email}</td>
          <td>${user.name}</td>
          <td>${user.plan}</td>
          <td>${user.createdAt.toDate().toLocaleDateString()}</td>
        </tr>
      `;
      tableBody.innerHTML += row;
    });
  } catch (error) {
    console.error("Error displaying table: ", error);
  }
}

// Call when page loads
displayUsersTable();
```

---

## Firestore Data Types

When creating fields, you can use these types:

| Type | Example | Description |
|------|---------|-------------|
| String | "John Doe" | Text |
| Number | 9.99 | Integer or decimal |
| Boolean | true/false | True or False |
| Date | 2025-12-09 | Date and time |
| Array | ["feature1", "feature2"] | List of values |
| Map | {name: "John", age: 30} | Object/nested data |
| Reference | users/abc123 | Link to another document |

---

## Security Rules (Important!)

After development, change your security rules from "test mode" to "production":

### Test Mode (Development Only)
```javascript
rules_version = '2';
service cloud.firestore {
  match /databases/{database}/documents {
    match /{document=**} {
      allow read, write: if true;  // Allow all (NOT SAFE)
    }
  }
}
```

### Production Mode (Secure)
```javascript
rules_version = '2';
service cloud.firestore {
  match /databases/{database}/documents {
    // Only authenticated users can access their own data
    match /users/{userId} {
      allow read, write: if request.auth.uid == userId;
    }
  }
}
```

---

## Step-by-Step Summary

1. ✅ Go to Firebase Console
2. ✅ Enable Firestore Database
3. ✅ Create Collection (e.g., "users")
4. ✅ Add Documents with fields
5. ✅ Use JavaScript to read/write data
6. ✅ Display data on your web page
7. ✅ Set up security rules

---

## Common Mistakes to Avoid

❌ **Don't:**
- Store sensitive data like passwords
- Use Production rules in development
- Forget to import Firestore libraries
- Store large files (use Storage instead)
- Make too many database calls

✅ **Do:**
- Use Collections for organized data
- Use Security Rules to protect data
- Cache data to reduce calls
- Use Indexes for queries
- Test security rules

---

## Quick Checklist

- [ ] Firestore enabled in Firebase Console
- [ ] Collection created (e.g., "users")
- [ ] Sample documents added
- [ ] Firebase imports added to HTML
- [ ] Add/Read/Update/Delete functions written
- [ ] Data displayed on web page
- [ ] Security rules configured
- [ ] Tested with real data

---

## Example Collections for PhishGuard

### Users Collection
```
users/
├── user1
│   ├── email: john@example.com
│   ├── plan: Pro
│   ├── createdAt: 2025-01-15
│   └── scansUsed: 45
│
├── user2
│   ├── email: jane@example.com
│   ├── plan: Free
│   ├── createdAt: 2025-01-10
│   └── scansUsed: 8
```

### Scan History Collection
```
scanHistory/
├── scan1
│   ├── userId: user1
│   ├── url: http://phishing-site.com
│   ├── result: malicious
│   ├── timestamp: 2025-12-09T14:30:00
│   └── signature: secure-update-login
│
├── scan2
│   ├── userId: user2
│   ├── url: http://google.com
│   ├── result: safe
│   ├── timestamp: 2025-12-09T15:45:00
│   └── signature: none
```

---

## Next Steps

1. Create Firestore collections
2. Add sample data
3. Write JavaScript functions to read/write
4. Test on your web page
5. Display data in tables
6. Add edit/delete functionality
7. Deploy to production

---

**Now you know how to create and use tables with Firebase! 🎉**

Questions? Let me know which part you'd like me to explain further!
