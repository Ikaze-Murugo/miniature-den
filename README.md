<div align="center">

# 🏠 Murugo - Real Estate Platform

**Find Your Perfect Home in Rwanda**

[![Laravel](https://img.shields.io/badge/Laravel-10.49.0-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.4.10-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![Status](https://img.shields.io/badge/Status-Active-brightgreen.svg)](https://github.com/Ikaze-Murugo/miniature-den)

*A comprehensive real estate platform connecting landlords and renters across Rwanda*

</div>

---

## 📋 Table of Contents

- [✨ Features](#-features)
- [🛠️ Tech Stack](#️-tech-stack)
- [🚀 Getting Started](#-getting-started)
- [📱 Screenshots](#-screenshots)
- [🏗️ Architecture](#️-architecture)
- [🗺️ Map Integration](#️-map-integration)
- [📊 Database Schema](#-database-schema)
- [🔧 API Endpoints](#-api-endpoints)
- [🧪 Testing](#-testing)
- [📈 Performance](#-performance)
- [🤝 Contributing](#-contributing)
- [📄 License](#-license)

---

## ✨ Features

### 🏡 **Property Management**
- **Advanced Property Listings** with detailed descriptions, images, and blueprints
- **Interactive Maps** with Mapbox integration showing property locations
- **Nearby Amenities** with walking distances to restaurants, schools, hospitals
- **Ground Blueprint Upload** supporting PDF, JPG, PNG formats
- **Property Search & Filtering** by type, price, bedrooms, amenities

### 👥 **User Management**
- **Multi-Role System** (Landlords, Renters, Admins)
- **Email Verification** and password reset functionality
- **Profile Management** with avatar uploads
- **Secure Authentication** with Laravel Sanctum

### 💬 **Communication**
- **Real-time Messaging** between landlords and renters
- **Message Reporting System** for inappropriate content
- **Notification System** for new messages and property updates

### 🛡️ **Security & Reporting**
- **Comprehensive Reporting System** for property and message issues
- **Admin Dashboard** with ticket management and analytics
- **Rate Limiting** to prevent abuse
- **Security Headers** and input validation

### 🗺️ **Location Services**
- **Automatic Geocoding** using Google Maps API
- **Proximity Calculations** with Haversine formula
- **Amenity Database** with 49+ local businesses and services
- **Map-based Property Search** with interactive filtering

---

## 🛠️ Tech Stack

<div align="center">

| Category | Technology | Version |
|----------|------------|---------|
| **Backend** | Laravel | 10.49.0 |
| **Language** | PHP | 8.4.10 |
| **Database** | SQLite | 3.x |
| **Frontend** | Blade Templates | - |
| **Styling** | Tailwind CSS | 3.x |
| **Maps** | Mapbox GL JS | 2.x |
| **Geocoding** | Google Maps API | - |
| **Build Tool** | Vite | 4.x |
| **Package Manager** | Composer | 2.x |

</div>

### 🔧 **Key Dependencies**
- **Laravel Framework** - Robust PHP framework
- **Laravel Sanctum** - API authentication
- **Mapbox GL JS** - Interactive maps
- **Tailwind CSS** - Utility-first CSS framework
- **Alpine.js** - Lightweight JavaScript framework

---

## 🚀 Getting Started

### 📋 Prerequisites
- PHP 8.4.10 or higher
- Composer 2.x
- Node.js 16+ and npm
- Mapbox API key
- Google Maps API key (optional)

### 🔧 Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/Ikaze-Murugo/miniature-den.git
   cd miniature-den
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure environment variables**
   ```env
   # Database
   DB_CONNECTION=sqlite
   DB_DATABASE=/absolute/path/to/database.sqlite
   
   # Mapbox
   MAPBOX_ACCESS_TOKEN=your_mapbox_token_here
   MAPBOX_STYLE_URL=mapbox://styles/mapbox/streets-v11
   
   # Google Maps (optional)
   GOOGLE_MAPS_API_KEY=your_google_maps_api_key
   ```

6. **Database setup**
   ```bash
   touch database/database.sqlite
   php artisan migrate
   php artisan db:seed
   ```

7. **Build assets**
   ```bash
   npm run build
   ```

8. **Start the development server**
   ```bash
   php artisan serve
   ```

Visit `http://localhost:8000` to see the application.

---

## 📱 Screenshots

<div align="center">

### 🏠 Homepage
![Homepage](docs/screenshots/homepage.png)

### 🗺️ Map Search
![Map Search](docs/screenshots/map-search.png)

### 📋 Property Details
![Property Details](docs/screenshots/property-details.png)

### 👤 Admin Dashboard
![Admin Dashboard](docs/screenshots/admin-dashboard.png)

</div>

---

## 🏗️ Architecture

### 📁 **Project Structure**
```
murugo_php/
├── app/
│   ├── Console/Commands/     # Artisan commands
│   ├── Http/Controllers/     # Application controllers
│   ├── Http/Middleware/      # Custom middleware
│   ├── Models/              # Eloquent models
│   └── Services/            # Business logic services
├── database/
│   ├── migrations/          # Database migrations
│   └── seeders/            # Database seeders
├── resources/
│   ├── views/              # Blade templates
│   ├── css/               # Stylesheets
│   └── js/                # JavaScript files
├── routes/                 # Application routes
└── storage/               # File storage
```

### 🔄 **Data Flow**
1. **User Registration** → Email Verification → Role Assignment
2. **Property Creation** → Geocoding → Amenity Proximity Calculation
3. **Search Request** → Filtering → Map Display → Results
4. **Message Exchange** → Real-time Updates → Notifications

---

## 🗺️ Map Integration

### 🎯 **Features**
- **Interactive Property Maps** with Mapbox GL JS
- **Amenity Markers** showing nearby services
- **Distance Calculations** with walking/driving times
- **Map-based Search** with real-time filtering
- **Responsive Design** for mobile and desktop

### 📍 **Location Services**
- **Automatic Geocoding** for address-to-coordinates conversion
- **Proximity Caching** for performance optimization
- **Amenity Database** with 49+ local businesses
- **Scheduled Updates** for proximity data

---

## 📊 Database Schema

### 🗃️ **Core Tables**
- `users` - User accounts and profiles
- `properties` - Property listings with location data
- `images` - Property images and blueprints
- `amenities` - Local businesses and services
- `property_amenities` - Cached proximity data
- `messages` - User communications
- `reports` - Issue reporting system

### 🔗 **Key Relationships**
- Users → Properties (One-to-Many)
- Properties → Images (One-to-Many)
- Properties → Amenities (Many-to-Many with pivot data)
- Users → Messages (One-to-Many)

---

## 🔧 API Endpoints

### 🏠 **Property Endpoints**
```
GET    /properties              # List properties
POST   /properties              # Create property
GET    /properties/{id}         # Show property
PUT    /properties/{id}         # Update property
DELETE /properties/{id}         # Delete property
GET    /properties/search-map   # Map-based search
```

### 👤 **User Endpoints**
```
POST   /register                # User registration
POST   /login                   # User login
POST   /logout                  # User logout
GET    /profile                 # User profile
PUT    /profile                 # Update profile
```

### 💬 **Message Endpoints**
```
GET    /messages                # List conversations
POST   /messages                # Send message
GET    /messages/{id}           # Show conversation
```

---

## 🧪 Testing

### 🔍 **Test Coverage**
- **Unit Tests** for models and services
- **Feature Tests** for API endpoints
- **Browser Tests** for user interactions
- **Integration Tests** for map functionality

### 🚀 **Running Tests**
```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Run with coverage
php artisan test --coverage
```

---

## 📈 Performance

### ⚡ **Optimizations**
- **Database Indexing** on frequently queried columns
- **Eager Loading** to prevent N+1 queries
- **Proximity Caching** for map data
- **Asset Optimization** with Vite
- **Rate Limiting** to prevent abuse

### 📊 **Performance Metrics**
- **Page Load Time** < 2 seconds
- **Database Queries** < 10 per page
- **Map Rendering** < 1 second
- **Search Response** < 500ms

---

## 🤝 Contributing

We welcome contributions! Please follow these steps:

1. **Fork the repository**
2. **Create a feature branch** (`git checkout -b feature/amazing-feature`)
3. **Commit your changes** (`git commit -m 'Add amazing feature'`)
4. **Push to the branch** (`git push origin feature/amazing-feature`)
5. **Open a Pull Request**

### 📝 **Code Standards**
- Follow PSR-12 coding standards
- Write comprehensive tests
- Update documentation
- Use meaningful commit messages

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

<div align="center">

### 🌟 **Star this repository if you found it helpful!**

**Built with ❤️ for the Rwanda real estate community**

[![GitHub stars](https://img.shields.io/github/stars/Ikaze-Murugo/miniature-den?style=social)](https://github.com/Ikaze-Murugo/miniature-den/stargazers)
[![GitHub forks](https://img.shields.io/github/forks/Ikaze-Murugo/miniature-den?style=social)](https://github.com/Ikaze-Murugo/miniature-den/network)

</div>