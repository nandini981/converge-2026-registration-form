<?php
// config.php - Configuration for Google Apps Script Integration

// ============================================
// GOOGLE SHEETS CONFIGURATION (Apps Script Method)
// ============================================
define('GOOGLE_SHEETS_ENABLED', true);
define('GOOGLE_APPS_SCRIPT_URL', 'https://script.google.com/macros/s/AKfycbzgwtMgGIceMjEvRHijpID8IbLI9KT56MNdDhqxxPOPzGCd3eMqHAGVWyviamS0pqPsZw/exec');

// Replace YOUR_DEPLOYMENT_ID with the actual ID from your Apps Script Web App URL
// Example: https://script.google.com/macros/s/AKfycbx.../exec
//                                              ^^^^^^^^^ This part

// ============================================
// OTHER SETTINGS
// ============================================

// Excel/CSV Export (Local Backup)
define('EXCEL_EXPORT_ENABLED', true);
define('EXCEL_FILE_PATH', 'registrations/converge_2026_registrations.csv');

// File Upload
define('UPLOAD_DIR', 'uploads/resumes');
define('MAX_FILE_SIZE', 5242880); // 5MB

// Email Configuration
define('EMAIL_ENABLED', true);
define('ADMIN_EMAIL', 'placement@pgdav.du.ac.in');

// Application Settings
define('APP_NAME', 'CONVERGE 2026');
define('TIMEZONE', 'Asia/Kolkata');
date_default_timezone_set(TIMEZONE);

// Brochure URL
define('BROCHURE_URL', 'https://yourwebsite.com/converge-brochure.pdf');

?>

<!-- 
QUICK SETUP CHECKLIST:
☐ Created Google Sheet with headers
☐ Copied Apps Script code
☐ Deployed as Web App
☐ Copied Web App URL
☐ Updated GOOGLE_APPS_SCRIPT_URL above
☐ Created folders: uploads/resumes, registrations
☐ Set folder permissions (755)
☐ Updated ADMIN_EMAIL
☐ Updated BROCHURE_URL
-->