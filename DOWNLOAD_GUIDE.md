# 📥 How to Download All Files

## **Quick Method - Copy & Save Each File**

Since I cannot create a zip file directly, here's the easiest way to get all the files:

---

## **📁 Files You Need (5 files total)**

### **1️⃣ index.html** 
**This is the main registration form**

✅ **Visible in the artifact preview above**
- Click on the artifact preview
- Right-click → "View Page Source" (or press Ctrl+U)
- Select all (Ctrl+A) → Copy (Ctrl+C)
- Create new file `index.html` → Paste → Save

**OR** - Copy the HTML directly from the chat artifact

---

### **2️⃣ submit_form.php**
**Backend PHP handler**

✅ **Available in artifact: "submit_form.php - Backend Handler"**
- Scroll to that artifact in the chat
- Copy the entire PHP code
- Create new file `submit_form.php` → Paste → Save

---

### **3️⃣ config.php**
**Configuration settings**

✅ **Available in artifact: "config.php - Configuration Settings"**
- Copy the PHP code from that artifact
- Create new file `config.php` → Paste → Save
- **IMPORTANT:** Update the settings with your details!

---

### **4️⃣ view_submissions.php**
**Admin dashboard to view all registrations**

✅ **Available in artifact: "view_submissions.php - Admin Dashboard"**
- Copy the PHP code
- Create new file `view_submissions.php` → Paste → Save
- **Change the admin password** in the file!

---

### **5️⃣ README.md**
**Complete setup guide**

✅ **Available in artifact: "README.md - Complete Setup Guide"**
- Copy the markdown content
- Create new file `README.md` → Paste → Save

---

## **🚀 Alternative: Create Files via Command Line**

If you're comfortable with terminal/command line:

### **On Windows (PowerShell):**
```powershell
# Create project structure
New-Item -ItemType Directory -Path "converge-registration"
cd converge-registration
New-Item -ItemType Directory -Path "uploads/resumes"
New-Item -ItemType Directory -Path "registrations"

# Create empty files
New-Item -ItemType File -Path "index.html"
New-Item -ItemType File -Path "submit_form.php"
New-Item -ItemType File -Path "config.php"
New-Item -ItemType File -Path "view_submissions.php"
New-Item -ItemType File -Path "README.md"
```

### **On Mac/Linux (Terminal):**
```bash
# Create project structure
mkdir -p converge-registration/uploads/resumes
mkdir -p converge-registration/registrations
cd converge-registration

# Create empty files
touch index.html submit_form.php config.php view_submissions.php README.md

# Set permissions
chmod 755 uploads/resumes registrations
chmod 644 *.php *.html
```

Then copy-paste the code from each artifact into the respective files.

---

## **📋 File Structure After Setup**

```
converge-registration/
│
├── index.html              ← Main form (copy from artifact)
├── submit_form.php         ← Backend handler (copy from artifact)
├── config.php              ← Configuration (copy from artifact)
├── view_submissions.php    ← Admin dashboard (copy from artifact)
├── README.md               ← Setup guide (copy from artifact)
│
├── uploads/
│   └── resumes/           ← Create this folder
│
└── registrations/
    └── (CSV files will be saved here)
```

---

## **✅ Checklist Before Starting**

- [ ] All 5 files copied and saved
- [ ] Folders created: `uploads/resumes` and `registrations`
- [ ] `config.php` updated with your settings
- [ ] Admin password changed in `view_submissions.php`
- [ ] Composer installed (for PHP dependencies)
- [ ] Web server running (Apache/Nginx)

---

## **🔧 Quick Start Commands**

After copying all files:

```bash
# Install PHP dependencies
composer require google/apiclient:"^2.0"
composer require phpmailer/phpmailer:"^6.8"

# Test the form
php -S localhost:8000

# Open in browser
# http://localhost:8000/index.html
```

---

## **💡 Pro Tip: Use a Code Editor**

Download a code editor like:
- **Visual Studio Code** (Recommended) - [code.visualstudio.com](https://code.visualstudio.com)
- **Sublime Text** - [sublimetext.com](https://sublimetext.com)
- **Notepad++** (Windows) - [notepad-plus-plus.org](https://notepad-plus-plus.org)

These make it easier to edit and manage all files!

---

## **🆘 Need Help?**

If you have trouble:
1. Make sure you copied **complete** code (from `<?php` to `?>` for PHP files)
2. Check file extensions are correct (`.html`, `.php`, not `.txt`)
3. Verify all folders exist
4. Read the detailed README.md for setup instructions

---

## **📞 Contact**

For assistance:
- Email: placement@pgdav.du.ac.in
- Website: https://www.pgdavplacementcell.in

---

**Happy Building! 🚀**

*The Placement Cell, P.G.D.A.V. College*