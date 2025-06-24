# SIMPERU - Sistem Informasi Manajemen Pengurus Perumahan
## Digital Signature & Letter Management System

Villa Windaro Permai Housing Management System with Digital Signature capabilities.

---

## 📋 Features Overview

### 🏠 **For Residents (Panel Warga)**
- **Digital Letter Creation**: Wizard-based interface for creating complaint letters
- **Real-time Status Tracking**: Monitor letter approval status
- **Digital Document Download**: Download officially signed letters with QR codes
- **Document Verification**: Verify letter authenticity using QR code scanner
- **Personal Dashboard**: Overview of all submitted letters and their status
- **Mobile-Friendly Interface**: Responsive design for mobile devices

### 👔 **For Admin (Panel Pengurus)**
- **Letter Review & Approval**: Comprehensive approval workflow
- **Digital Signature Generation**: Automatic digital signature with SHA-256 hash
- **QR Code Integration**: Generate QR codes for document verification
- **Family Data Management**: Manage resident family information
- **Financial Management**: Track fees and payments
- **Announcement System**: Broadcast important information

### 🔐 **Digital Signature System**
- **SHA-256 Hash Encryption**: Tamper-proof digital signatures
- **QR Code Verification**: Public verification without exposing sensitive data
- **PDF Generation**: Professional letterhead with embedded signatures
- **Audit Trail**: Complete logging of all signature activities
- **Legal Compliance**: Meets digital signature standards for official documents

---

## 🎯 Letter Categories

The system supports various letter types with unique numbering:

| Code | Category | Description |
|------|----------|-------------|
| **LNG** | Surat Lingkungan | Environment, waste, and security complaints |
| **FST** | Surat Fasilitas | Public facility complaints |
| **KLH** | Keterangan Kelahiran | Birth certificate requests |
| **KMT** | Keterangan Kematian | Death certificate requests |
| **IZA** | Izin Acara | Event permission requests |
| **PMT** | Peminjaman Tempat | Facility booking requests |

---

## 🛠 Installation & Setup

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL/PostgreSQL

### 1. Clone & Install Dependencies
```bash
git clone [repository-url]
cd Simperu
composer install
npm install
```

### 2. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

Configure your database and other settings in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=simperu
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Digital Signature Settings
SIGNATURE_EXPIRY_DAYS=365
SIGNATURE_ALGORITHM=sha256
```

### 3. Install QR Code Package
```bash
composer require simplesoftwareio/simple-qrcode
```

### 4. Database Setup
```bash
php artisan migrate
php artisan db:seed
```

### 5. Storage Setup
```bash
php artisan storage:link
mkdir -p storage/app/public/{signatures,letters,complaint-attachments}
```

### 6. Build Assets
```bash
npm run build
# For development:
npm run dev
```

### 7. Create Admin User
```bash
php artisan make:filament-user
```

### 8. Start Development Server
```bash
php artisan serve
```

---

## 🚀 Usage Guide

### **For Residents**

#### Creating a Letter
1. **Login** to resident panel at `/resident`
2. **Navigate** to \"Surat Pengaduan\" → \"Create\"
3. **Follow the Wizard**:
   - **Step 1**: Select letter category and priority
   - **Step 2**: Write detailed content and upload attachments
   - **Step 3**: Review and confirm submission
4. **Submit** and receive confirmation with tracking number

#### Tracking Letter Status
- **Dashboard**: View quick statistics and recent letters
- **Letter List**: See all submitted letters with status badges
- **Status Types**:
  - 🟡 **Pending**: Waiting for admin review
  - 🔵 **In Review**: Currently being processed
  - 🟢 **Approved**: Approved and digitally signed
  - 🔴 **Rejected**: Rejected with admin notes

#### Downloading Signed Letters
1. **Wait for Approval**: Letter must be approved by admin
2. **Download PDF**: Click download button on approved letters
3. **Verify Authenticity**: Use QR code to verify document

### **For Admin**

#### Reviewing Letters
1. **Access Admin Panel** at `/admin`
2. **Navigate** to \"Surat Pengaduan\"
3. **Review Details**: Click on any letter to view full content
4. **Make Decision**:
   - **Approve**: Add optional notes and approve
   - **Reject**: Provide mandatory rejection reason

#### Digital Signature Process
When approving a letter:
1. **Automatic Generation**: Digital signature is created automatically
2. **PDF Creation**: Professional PDF with letterhead and QR code
3. **Hash Generation**: SHA-256 hash for verification
4. **QR Code Embedding**: Verification QR code added to document

---

## 🔍 Digital Signature Verification

### How It Works
1. **Scan QR Code** on any official document
2. **Automatic Verification** checks signature hash
3. **Display Results**:
   - ✅ **Valid**: Shows signer details and timestamp
   - ❌ **Invalid**: Shows error and possible reasons

### Verification URL
Public verification available at: `/letter/verify/{hash}`

### Security Features
- **Tamper Detection**: Any document modification invalidates signature
- **Timestamp Recording**: Permanent record of signing time
- **Public Verification**: Anyone can verify without system access
- **No Data Exposure**: Verification shows minimal sensitive information

---

## 📱 User Experience Features

### **Modern Interface**
- **Responsive Design**: Works perfectly on mobile and desktop
- **Dark Mode Support**: Automatic dark mode detection
- **Intuitive Navigation**: User-friendly menu structure
- **Real-time Updates**: Live status updates and notifications

### **Wizard-Based Forms**
- **Step-by-Step Guidance**: Easy form completion process
- **Progress Indicators**: Clear visual progress tracking
- **Validation Feedback**: Instant validation and error messages
- **Auto-save**: Progress saved between steps

### **Dashboard Analytics**
- **Quick Statistics**: Overview of letter counts and status
- **Recent Activity**: Latest letters and updates
- **Action Shortcuts**: Quick access to common tasks
- **Help & Tips**: Integrated help system

---

## 🏗 Technical Architecture

### **Backend Stack**
- **Laravel 11**: Modern PHP framework
- **Filament 3**: Admin panel and forms
- **MySQL**: Database management
- **Queue System**: Background job processing

### **Frontend Stack**
- **Alpine.js**: Reactive JavaScript framework
- **Tailwind CSS**: Utility-first CSS framework
- **Livewire**: Dynamic interface components

### **Digital Signature Stack**
- **DomPDF**: PDF generation
- **Simple QrCode**: QR code generation
- **SHA-256**: Cryptographic hashing
- **Custom Service**: Digital signature management

### **File Structure**
```
app/
├── Filament/
│   ├── Resources/           # Admin resources
│   └── Resident/           # Resident panel
│       ├── Resources/      # Resident-specific resources
│       ├── Pages/          # Custom pages
│       └── Widgets/        # Dashboard widgets
├── Http/Controllers/       # Route controllers
├── Models/                 # Eloquent models
└── Services/               # Business logic services

resources/
├── views/
│   ├── filament/          # Custom Filament views
│   ├── pdf/               # PDF templates
│   └── verification/      # Signature verification pages
└── css/filament/          # Custom styling
```

---

## 🔧 Configuration

### Digital Signature Settings
```php
// config/digital_signature.php
return [
    'algorithm' => 'sha256',
    'expiry_days' => 365,
    'letterhead' => [
        'title' => 'Perumahan Villa Windaro Permai',
        'address' => 'Jl. Amarta, RT 03/RW 01...',
    ],
    // ... more settings
];
```

### Customization Options
- **Letter Templates**: Modify PDF templates in `resources/views/pdf/`
- **Styling**: Update CSS in `resources/css/filament/resident/`
- **Letterhead**: Configure in `config/digital_signature.php`
- **Categories**: Add new letter types via seeder or admin panel

---

## 📊 Database Schema

### Key Tables
- **users**: User authentication and basic info
- **families**: Family data from CSV import
- **complaint_letters**: All letter data and signatures
- **letter_categories**: Letter types and templates
- **digital signatures**: Signature metadata and verification

### Relationships
- User hasMany ComplaintLetters
- ComplaintLetter belongsTo LetterCategory
- ComplaintLetter belongsTo User (signedBy, submittedBy, processedBy)

---

## 🛡 Security Features

### **Authentication & Authorization**
- **Role-based Access**: Separate admin and resident permissions
- **Session Management**: Secure session handling
- **CSRF Protection**: Built-in Laravel CSRF protection

### **Digital Signature Security**
- **SHA-256 Hashing**: Industry-standard cryptographic hashing
- **Tamper Detection**: Any modification invalidates signature
- **Key Management**: Secure key storage and management
- **Audit Logging**: Complete signature activity logging

### **Data Protection**
- **Input Validation**: Comprehensive form validation
- **SQL Injection Prevention**: Eloquent ORM protection
- **XSS Protection**: Built-in output escaping
- **File Upload Security**: Validated file types and sizes

---

## 🚀 Deployment

### Production Checklist
- [ ] Set `APP_ENV=production` in `.env`
- [ ] Configure production database
- [ ] Set up proper file permissions
- [ ] Configure web server (Nginx/Apache)
- [ ] Set up SSL certificate
- [ ] Configure backup strategy
- [ ] Set up monitoring and logging

### Server Requirements
- **PHP 8.2+** with required extensions
- **MySQL 8.0+** or PostgreSQL 13+
- **Redis** (recommended for sessions/cache)
- **Supervisor** (for queue workers)

---

## 📈 Future Enhancements

### Planned Features
- **Mobile App**: Native mobile application
- **Email Notifications**: Automated email updates
- **Telegram Integration**: Bot notifications
- **Multi-language Support**: Indonesian and English
- **Advanced Analytics**: Detailed reporting dashboard
- **API Integration**: REST API for third-party integrations

### Scalability Considerations
- **Queue System**: Background job processing
- **Cache Strategy**: Redis/Memcached integration
- **CDN Integration**: Static asset delivery
- **Database Optimization**: Indexing and query optimization

---

## 🤝 Contributing

### Development Workflow
1. **Fork Repository**
2. **Create Feature Branch**: `git checkout -b feature/amazing-feature`
3. **Commit Changes**: `git commit -m 'Add amazing feature'`
4. **Push Branch**: `git push origin feature/amazing-feature`
5. **Open Pull Request**

### Code Standards
- **PSR-12**: PHP coding standards
- **Laravel Conventions**: Follow Laravel best practices
- **Testing**: Write tests for new features
- **Documentation**: Update documentation for changes

---

## 📞 Support & Contact

### Technical Support
- **Email**: admin@villawindaro.com
- **Phone**: (0761) 123456
- **WhatsApp**: 081234567890

### System Information
- **Version**: 1.0.0
- **Laravel**: 11.x
- **Filament**: 3.x
- **PHP**: 8.2+

### Known Issues
- Large file uploads may timeout on slow connections
- QR code scanning requires good camera/lighting
- PDF generation may be slow for complex documents

---

## 📄 License

This project is licensed under the MIT License. See LICENSE file for details.

---

## 📚 Additional Resources

### Documentation Links
- [Laravel Documentation](https://laravel.com/docs)
- [Filament Documentation](https://filamentphp.com/docs)
- [Digital Signature Standards](https://en.wikipedia.org/wiki/Digital_signature)

### Training Materials
- User training videos available on request
- Admin training manual included in `/docs` folder
- API documentation available at `/api/documentation`

---

**Made with ❤️ for Villa Windaro Permai Community**

*Last Updated: June 2025*
