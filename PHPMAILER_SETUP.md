# PHPMailer Setup Guide

Your contact form is now configured to use PHPMailer with Gmail SMTP. Follow these steps to complete the setup:

## Step 1: Install PHPMailer via Composer

### Option A: Using Composer (Recommended)

1. **Install Composer** (if you don't have it):
   - Download from: https://getcomposer.org/download/
   - Run the installer for Windows

2. **Open PowerShell/Terminal** in your portfolio folder:
   ```powershell
   cd C:\xampp\htdocs\portfolio
   ```

3. **Install PHPMailer**:
   ```powershell
   composer require phpmailer/phpmailer
   ```

This will create a `vendor` folder with PHPMailer installed.

### Option B: Manual Installation (If Composer doesn't work)

1. Download PHPMailer from: https://github.com/PHPMailer/PHPMailer/archive/master.zip
2. Extract the ZIP file
3. Copy the `PHPMailer-master/src` folder to your portfolio folder
4. Rename it to `PHPMailer`
5. Update `contact.php` line 11 to:
   ```php
   require 'PHPMailer/PHPMailer.php';
   require 'PHPMailer/SMTP.php';
   require 'PHPMailer/Exception.php';
   ```

---

## Step 2: Get Gmail App Password

**IMPORTANT:** You CANNOT use your regular Gmail password. You need an App Password.

### Steps to Create Gmail App Password:

1. **Go to your Google Account**: https://myaccount.google.com/

2. **Enable 2-Step Verification** (if not already enabled):
   - Go to Security → 2-Step Verification
   - Follow the prompts to set it up

3. **Create App Password**:
   - Go to Security → 2-Step Verification → App passwords
   - Or directly: https://myaccount.google.com/apppasswords
   - Select "Mail" and "Other (custom name)"
   - Name it: "Portfolio Contact Form"
   - Click **Generate**

4. **Copy the 16-character password** (it looks like: `abcd efgh ijkl mnop`)

5. **Update contact.php**:
   - Open `contact.php`
   - Find line 68: `$mail->Password   = 'YOUR_APP_PASSWORD_HERE';`
   - Replace `YOUR_APP_PASSWORD_HERE` with your app password
   - **Remove the spaces** in the password (should be: `abcdefghijklmnop`)

Example:
```php
$mail->Password   = 'abcdefghijklmnop';  // Your 16-char app password
```

---

## Step 3: Test Your Contact Form

1. **Start Apache** in XAMPP
2. **Open your portfolio**: `http://localhost/portfolio`
3. **Scroll to Contact section**
4. **Fill out the form** and submit
5. **Check your email** (nagnalhardycloyd0@gmail.com)

---

## Troubleshooting

### Error: "Class 'PHPMailer' not found"
- **Solution**: PHPMailer is not installed. Go back to Step 1.

### Error: "SMTP connect() failed"
- **Solution**: Check your Gmail App Password is correct (no spaces)
- **Solution**: Make sure 2-Step Verification is enabled on your Google account

### Error: "SMTP Error: Could not authenticate"
- **Solution**: Your app password is incorrect. Generate a new one.

### Messages not sending but no error?
- **Solution**: Check if messages are being logged to `contact_messages.txt`
- **Solution**: Check your spam folder in Gmail

### Less Secure Apps Error
- **Solution**: Use App Password instead (Google requires this now)

---

## Security Tips

1. **Never commit your app password to Git**
   - The `.gitignore` file already excludes sensitive files
   
2. **Consider using environment variables** for production:
   ```php
   $mail->Password = getenv('GMAIL_APP_PASSWORD');
   ```

3. **Keep PHPMailer updated**:
   ```powershell
   composer update phpmailer/phpmailer
   ```

---

## Alternative SMTP Services

If Gmail doesn't work, you can use:
- **SendGrid** (free tier available)
- **Mailgun** (free tier available)
- **Amazon SES** (very cheap)

Just change the SMTP settings in `contact.php` lines 65-69.

---

## Current Configuration

- **SMTP Host**: smtp.gmail.com
- **Port**: 587
- **Encryption**: STARTTLS
- **Username**: nagnalhardycloyd0@gmail.com
- **Password**: (Your App Password - needs to be set)

---

## Next Steps

1. ✅ Install PHPMailer (Step 1)
2. ✅ Get Gmail App Password (Step 2)
3. ✅ Update contact.php with your app password
4. ✅ Test the form
5. ✅ Celebrate! 🎉

Need help? Check the PHPMailer documentation: https://github.com/PHPMailer/PHPMailer
