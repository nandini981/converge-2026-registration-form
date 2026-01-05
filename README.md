# CONVERGE 2026 - Registration Form System
**The Placement Cell, P.G.D.A.V. College**

A complete registration system for CONVERGE 2026 - The Annual Internship Fair, featuring automatic data export to Google Sheets, Excel, and email notifications.

---

## 📋 **Features**

✅ Beautiful, responsive form matching PGDAV branding  
✅ Real-time form validation  
✅ Resume upload with file validation  
✅ Interactive company selection interface  
✅ Automatic data export to Google Sheets  
✅ Local CSV/Excel backup  
✅ Email confirmation to students  
✅ Admin email notifications  
✅ MySQL database support (optional)  
✅ Unique registration ID generation  

---

## 📁 **File Structure**

```
converge-registration/
│
├── index.html              # Main registration form
├── submit_form.php         # Backend form handler
├── config.php              # Configuration settings
├── credentials.json        # Google API credentials (create this)
├── composer.json           # PHP dependencies (create this)
│
├── uploads/
│   └── resumes/           # Resume uploads directory
│
├── registrations/
│   └── converge_2026_registrations.csv  # Local data backup
│
└── README.md              # This file
```

---

## 🚀 **Quick Setup Guide**

### **Step 1: File Setup**

1. Create project folder: `mkdir converge-registration && cd converge-registration`
2. Copy all files (`index.html`, `submit_form.php`, `config.php`)
3. Create directories:
   ```bash
   mkdir -p uploads/resumes
   mkdir -p registrations
   chmod 755 uploads/resumes registrations
   ```

### **Step 2: Install PHP Dependencies**

Create `composer.json`:
```json
{
    "require": {
        "google/apiclient": "^2.0",
        "phpmailer/phpmailer": "^6.8"
    }
}
```

Install dependencies:
```bash
composer install
```

### **Step 3: Google Sheets Setup (EASIEST METHOD)**

#### **Option A: Using Google Apps Script (Recommended)**

1. **Create a Google Sheet** with these column headers in Row 1:
   ```
   Registration ID | Timestamp | Name | Email | Phone | WhatsApp | College | Course | Year | Companies | Referral | Additional Info | Resume Name
   ```

2. **Open Apps Script** (Extensions → Apps Script)

3. **Paste this code**:
   ```javascript
   function doPost(e) {
     try {
       var sheet = SpreadsheetApp.getActiveSpreadsheet().getActiveSheet();
       var data = JSON.parse(e.postData.contents);
       
       sheet.appendRow([
         data.registration_id,
         data.timestamp,
         data.name,
         data.email,
         data.phone,
         data.whatsapp,
         data.college,
         data.course,
         data.year,
         data.companies,
         data.referral,
         data.additional,
         data.resume_name
       ]);
       
       return ContentService.createTextOutput(JSON.stringify({
         'success': true,
         'message': 'Data saved successfully'
       })).setMimeType(ContentService.MimeType.JSON);
       
     } catch(error) {
       return ContentService.createTextOutput(JSON.stringify({
         'success': false,
         'message': error.toString()
       })).setMimeType(ContentService.MimeType.JSON);
     }
   }
   ```

4. **Deploy** → New deployment → Select "Web app"
   - Execute as: **Me**
   - Who has access: **Anyone**
   - Click **Deploy**

5. **Copy the Web App URL** (looks like: `https://script.google.com/macros/s/.../exec`)

6. **Update `submit_form.php`** - Add this function:
   ```php
   function saveToGoogleSheets($data) {
       $url = 'YOUR_APPS_SCRIPT_WEB_APP_URL';
       
       $postData = json_encode($data);
       
       $ch = curl_init($url);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_POST, true);
       curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
       curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
       
       $response = curl_exec($ch);
       curl_close($ch);
       
       return true;
   }
   ```

#### **Option B: Using Google Sheets API (Advanced)**

1. Go to [Google Cloud Console](https://console.cloud.google.com)
2. Create new project: "CONVERGE 2026"
3. Enable **Google Sheets API**
4. Create **Service Account** credentials
5. Download JSON key file → Save as `credentials.json`
6. Open your Google Sheet → Share with service account email
7. Copy Sheet ID from URL (between `/d/` and `/edit`)
8. Update `config.php` with your Sheet ID

### **Step 4: Configure Settings**

Edit `config.php`:

```php
// Google Sheets (if using Apps Script, set to false)
define('GOOGLE_SHEETS_ENABLED', true);
define('GOOGLE_SHEET_ID', 'YOUR_SHEET_ID_HERE');

// Excel Export (Always keep enabled for backup)
define('EXCEL_EXPORT_ENABLED', true);

// Email Settings
define('EMAIL_ENABLED', true);
define('ADMIN_EMAIL', 'placement@pgdav.du.ac.in');

// Brochure Link
define('BROCHURE_URL', 'https://yourwebsite.com/converge-brochure.pdf');
```

### **Step 5: Database Setup (Optional)**

If using MySQL:

```sql
CREATE DATABASE pgdav_converge;

USE pgdav_converge;

CREATE TABLE registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    registration_id VARCHAR(50) UNIQUE NOT NULL,
    timestamp DATETIME NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    whatsapp VARCHAR(20) NOT NULL,
    college VARCHAR(200) NOT NULL,
    course VARCHAR(100) NOT NULL,
    year VARCHAR(20) NOT NULL,
    companies TEXT NOT NULL,
    referral VARCHAR(50),
    additional TEXT,
    resume_path VARCHAR(255),
    resume_name VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_timestamp (timestamp)
);
```

Update `config.php`:
```php
define('DB_ENABLED', true);
define('DB_HOST', 'localhost');
define('DB_NAME', 'pgdav_converge');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
```

### **Step 6: Email Configuration**

For Gmail:
1. Enable 2-Step Verification
2. Generate **App Password**: Account → Security → App passwords
3. Update `config.php`:
   ```php
   define('SMTP_USERNAME', 'your-email@gmail.com');
   define('SMTP_PASSWORD', 'your-app-password');
   ```

### **Step 7: Test the Form**

1. Open `index.html` in browser
2. Fill out test submission
3. Check:
   - ✅ Google Sheet updated
   - ✅ CSV file created in `registrations/`
   - ✅ Resume uploaded to `uploads/resumes/`
   - ✅ Confirmation email received
   - ✅ Admin notification received

---

## 🎨 **Customization**

### **Update Brochure Link**

In `index.html`, find:
```javascript
const brochureLink = document.getElementById('brochureLink');
brochureLink.href = 'YOUR_BROCHURE_PDF_URL';
```

### **Update College Logo**

Add this in the header section of `index.html`:
```html
<img src="pgdav-logo.png" alt="PGDAV Logo" style="height: 80px;">
```

### **Change Color Scheme**

In `index.html`, modify CSS variables:
```css
:root {
    --pgdav-green: #0d5c4b;  /* Main color */
    --accent-gold: #d4af37;   /* Accent color */
}
```

### **Add/Remove Company Options**

In `index.html`, modify the `companies` array:
```javascript
const companies = [
    { name: "Your Company", icon: "🏢" },
    // Add more companies
];
```

---

## 📊 **Data Management**

### **View Submissions**

**Google Sheets**: Open your sheet URL  
**CSV File**: `registrations/converge_2026_registrations.csv`  
**Database**: Use phpMyAdmin or MySQL client

### **Export to Excel**

The CSV file can be opened in Excel directly. For better formatting:
1. Open Excel
2. Data → From Text/CSV
3. Select `converge_2026_registrations.csv`
4. Choose UTF-8 encoding
5. Import

### **Backup Data**

```bash
# Backup CSV
cp registrations/converge_2026_registrations.csv backups/backup_$(date +%Y%m%d).csv

# Backup database
mysqldump -u username -p pgdav_converge > backup_$(date +%Y%m%d).sql

# Backup resumes
tar -czf resumes_backup_$(date +%Y%m%d).tar.gz uploads/resumes/
```

---

## 🔒 **Security Considerations**

1. **File Upload Security**
   - Only PDF, DOC, DOCX allowed
   - 5MB size limit enforced
   - Unique filenames generated
   - Stored outside web root if possible

2. **Input Validation**
   - All inputs sanitized
   - Email validation
   - Phone number format check

3. **HTTPS Required**
   - Use SSL certificate
   - Never submit forms over HTTP

4. **File Permissions**
   ```bash
   chmod 644 *.php
   chmod 644 *.html
   chmod 755 uploads/resumes
   chmod 600 credentials.json
   chmod 600 config.php
   ```

---

## 🐛 **Troubleshooting**

### **Form Not Submitting**

1. Check browser console for errors (F12)
2. Verify `submit_form.php` path is correct
3. Check PHP error logs: `/var/log/apache2/error.log`

### **Google Sheets Not Updating**

1. Verify service account has edit access
2. Check credentials.json file exists
3. Ensure Sheet ID is correct
4. Test Apps Script deployment URL

### **Emails Not Sending**

1. Check SMTP credentials in config.php
2. Verify Gmail App Password is correct
3. Check spam folder
4. Enable error reporting in PHP

### **File Upload Failing**

1. Check directory permissions: `chmod 755 uploads/resumes`
2. Verify PHP `upload_max_filesize` and `post_max_size`
3. Check disk space: `df -h`

---

## 📞 **Support**

For issues or questions:
- Email: placement@pgdav.du.ac.in
- Website: https://www.pgdavplacementcell.in

---

## 📝 **License & Credits**

Created for **The Placement Cell, P.G.D.A.V. College**  
Event: **CONVERGE 2026 - The Annual Internship Fair**  

Form designed with ❤️ for PGDAV students

---

## ✅ **Pre-Launch Checklist**

- [ ] All files uploaded to server
- [ ] Directory permissions set correctly
- [ ] Google Sheets/Apps Script configured
- [ ] Config.php updated with correct values
- [ ] Brochure link updated
- [ ] Test submission completed successfully
- [ ] Email notifications working
- [ ] Mobile responsiveness tested
- [ ] SSL certificate installed
- [ ] Backup system in place

---

**Good luck with CONVERGE 2026! 🚀**