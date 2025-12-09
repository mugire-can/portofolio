# 🛡️ Cybersecurity Portfolio

A modern, responsive portfolio website showcasing cybersecurity expertise, projects, certifications, and experience. Built with HTML, CSS, JavaScript, and PHP with dynamic form handling and email notifications.


---

## ✨ Features

- 🎨 **Responsive Design** - Works on desktop, tablet, and mobile devices
- 📱 **Modern UI** - Cybersecurity-themed dark blue gradient design
- 🔐 **Dynamic Contact Form** - Server-side validation and automatic email notifications
- 📧 **Email Notifications** - Submissions sent to mugirecan@gmail.com
- 📥 **CV Download** - One-click CV download functionality
- 🔒 **Security Features** - XSS protection, input sanitization, email validation
- 📊 **Project Showcase** - Display featured projects with technologies
- 📜 **Certifications** - List all professional certifications
- 💼 **Experience & Education** - Timeline-based work history and education
- 📝 **Form Logging** - Automatic submission tracking and logging

---

## 🚀 Quick Start

### Prerequisites
- PHP 7.4 or higher
- Web server (Apache, Nginx) or PHP built-in server

### Local Development

1. **Clone the repository**
```bash
git clone https://github.com/mugire-can/portofolio.git
cd portofolio
```

2. **Start PHP server**
```bash
php -S localhost:8000
```

3. **Open in browser**
- Dynamic version: `http://localhost:8000/my_portfolio.php`
- Auto-redirect: `http://localhost:8000`

### Production Deployment

1. Upload all files to your web hosting
2. Ensure PHP is enabled on your server
3. Make the folder writable for form submission logs
4. Access: `yourdomain.com/my_portfolio.php`

---

## 📁 Project Structure

```
portofolio/
├── my_portfolio.php .................. Main portfolio (dynamic)
├── index.php ......................... Entry point (redirects to portfolio)
├── assets/
│   ├── css/
│   │   └── my_portfolio.css ......... Stylesheet
│   └── js/
│       └── my_portofolio.js ......... Client-side functionality
├── images/ ........................... Image assets
├── Mugire CAN CV.pdf ................. CV for download
├── .htaccess ......................... Server configuration
├── .gitignore ........................ Git configuration
└── README.md ......................... This file
```

---

## 📋 Features Explained

### Contact Form
The contact form includes:
- **Server-side validation** - All fields required
- **Email format checking** - Valid email format required
- **Email notifications** - Automatically sends beautiful HTML emails to mugirecan@gmail.com
- **XSS protection** - Input sanitization with htmlspecialchars()
- **Automatic logging** - Submissions saved to `submissions.log` with email status
- **User feedback** - Success/error messages displayed
- **Form persistence** - Data retained on validation errors

**Test the form:**
1. Fill all fields (First Name, Last Name, Email, Subject, Message)
2. Click "Send Message"
3. See success message
4. Check your email inbox (mugirecan@gmail.com) for the formatted submission
5. Check `submissions.log` for entry with email send status

### Portfolio Sections

#### 1. **Header**
- Navigation menu with smooth scrolling
- Download CV button
- Copy email button
- Professional tagline

#### 2. **About**
- Personal introduction
- Professional philosophy

#### 3. **Core Expertise**
- 6 skill categories with bullet points
- Organized grid layout
- Responsive design

#### 4. **Featured Projects**
- 3 example projects
- Project descriptions
- Technology badges
- Links to projects

#### 5. **Certifications**
- Professional certifications list
- Certification status
- Training details

#### 6. **Experience & Education**
- Work experience timeline
- Educational background
- Key achievements
- Role descriptions

#### 7. **Contact**
- Full contact form
- Form validation
- Submission logging
- User feedback system

---

## 🔒 Security Features

### Implemented Security Measures

1. **XSS Protection**
   - Uses `htmlspecialchars()` to escape output
   - Prevents JavaScript injection attacks

2. **Email Validation**
   - Uses `filter_var()` with FILTER_VALIDATE_EMAIL
   - Ensures valid email format

3. **Input Sanitization**
   - Trims whitespace
   - Sanitizes special characters
   - Removes potentially harmful input

4. **Session Management**
   - PHP session support enabled
   - Ready for future user tracking

5. **Log File Protection**
   - `.htaccess` prevents direct access to logs
   - Automatic file creation with proper permissions

---

## 📝 Form Submission Log

### Log File: `submissions.log`

Each submission is logged with:
```
2025-12-09 13:51:07 | John Doe | john@example.com | Project Inquiry
```

**Format**: `TIMESTAMP | NAME | EMAIL | SUBJECT`

### Viewing Logs
```bash
# View all submissions
cat submissions.log

# View last 10 submissions
tail -10 submissions.log

# Clear logs
rm submissions.log
```

---

## 🎨 Customization

### Update Personal Information

Edit `my_portfolio.php` to customize:

1. **Portfolio Data** (JavaScript object in assets/js/my_portofolio.js)
```javascript
const portfolioData = {
  personal: {
    firstName: "Your Name",
    lastName: "Your Surname",
    email: "your@email.com",
    phone: "+1 (XXX) XXX-XXXX",
    // ...
  }
}
```

2. **Skills** - Add/remove skill categories and items

3. **Projects** - Update with your projects

4. **Experience** - Add your work history

5. **Education** - List your qualifications

### Styling

Modify `assets/css/my_portfolio.css` to:
- Change color scheme
- Update typography
- Adjust layout
- Add custom styling

### Colors (CSS Variables)
```css
--primary: #0d1b2a;      /* Dark blue */
--secondary: #1e3a5f;    /* Medium blue */
--accent: #00d4ff;       /* Cyan */
--danger: #ff4757;       /* Red */
--success: #2ed573;      /* Green */
```

---

## 🔧 API Endpoints

### Form Submission

**URL**: `my_portfolio.php`  
**Method**: `POST`

**Parameters**:
```
name (string, required)
surname (string, required)
email (string, required, valid format)
subject (string, required)
message (string, required)
```

**Response**:
- Success (200): Green message + logged submission
- Error (400): Red message + form data retained

---

## 📱 Responsive Design

The portfolio is optimized for:
- **Desktop** (1920px+) - Full layout
- **Laptop** (1024px-1920px) - Optimized spacing
- **Tablet** (768px-1024px) - Responsive grid
- **Mobile** (320px-768px) - Stacked layout

---

## 🚀 Performance

- **Lightweight** - Minimal CSS/JS files
- **Fast Loading** - Optimized assets
- **No Dependencies** - Pure HTML/CSS/JS
- **Efficient** - No external libraries required

---

## 📧 Email Notifications

Email notifications are **already implemented** and fully functional!

### How It Works:
1. When someone submits the contact form, an email is automatically sent to **mugirecan@gmail.com**
2. The email includes a beautifully formatted HTML template with:
   - Sender's name and email
   - Subject line
   - Full message content
   - Submission timestamp
   - Professional cybersecurity-themed styling

### Email Configuration:
The portfolio email is configured in `my_portfolio.php` at line 27:
```php
$portfolio_email = "mugirecan@gmail.com";
```

To change the email address, simply update this line:
```php
$portfolio_email = "your-email@example.com";
```

### Email Features:
- ✅ HTML-formatted emails
- ✅ Automatic reply-to header set to sender's email
- ✅ Professional styling with cybersecurity theme colors
- ✅ Line breaks preserved in message content
- ✅ Timestamp included in email
- ✅ Email status logged (Sent/Failed) in submissions.log

---

## 🐛 Troubleshooting

### Form Not Submitting
- Check PHP is enabled on server
- Ensure folder has write permissions
- Verify form fields are filled

### 404 Errors on Assets
- Check file paths are relative
- Verify assets/ folder structure
- Clear browser cache

### Log File Not Created
- Ensure folder is writable
- Check server permissions
- Verify PHP can write files

---

## 📄 License

This project is open source and available for personal and commercial use.

---

## 👤 Author

**Mugire CAN**  
Cybersecurity Specialist  
[Email](mailto:mugire@email.com) | [GitHub](https://github.com/mugire-can)

---

## 🤝 Contributing

Feel free to fork this repository and customize it for your own portfolio!

### Steps:
1. Fork the repository
2. Create a feature branch (`git checkout -b feature/improvement`)
3. Commit changes (`git commit -m 'Add improvement'`)
4. Push to branch (`git push origin feature/improvement`)
5. Open a Pull Request

---

## 📞 Support

For issues or questions:
1. Check the documentation
2. Review the code comments
3. Submit an issue on GitHub

---

## 🎯 Future Enhancements

Potential improvements:
- [ ] Email notification system
- [ ] Admin panel for submissions
- [ ] Database integration
- [ ] reCAPTCHA protection
- [ ] File upload support
- [ ] Blog section
- [ ] Dark/light mode toggle
- [ ] Multi-language support

---

## 📊 Project Stats

- **Lines of Code**: 300+
- **CSS Rules**: 200+
- **JavaScript Functions**: 15+
- **Security Checks**: 6+
- **Responsive Breakpoints**: 4

---

## ✨ Highlights

✅ **Production Ready** - Fully functional and secure  
✅ **SEO Optimized** - Proper meta tags and structure  
✅ **Accessible** - WCAG compliant design  
✅ **Mobile First** - Responsive on all devices  
✅ **Well Documented** - Code comments throughout  
✅ **Easy to Customize** - Clear structure and variables  

---

## 📝 Changelog

### Version 1.0 (Current)
- Initial release
- Dynamic PHP portfolio
- Form submission logging
- Security features
- Responsive design

---

**Last Updated**: December 9, 2025  
**Status**: ✅ Production Ready  
**Version**: 1.0  
**Author**: Mugire CAN  
**Email**: mugirecan@gmail.com

