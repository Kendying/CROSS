import React from 'react';
import { Link } from 'react-router-dom';
import { Header } from '../components/Header';
import { 
  ArrowRight, 
  GraduationCap, 
  Users, 
  BarChart3, 
  Award,
  Shield,
  Heart,
  BookOpen,
  Target,
  Church,
  Calendar,
  MapPin,
  Phone,
  Mail,
  HelpingHand
} from 'lucide-react';

export const Home: React.FC = () => {
  const features = [
    {
      icon: GraduationCap,
      title: 'Scholarship Management',
      description: 'Comprehensive system for managing scholarships and empowering students through education.'
    },
    {
      icon: Users,
      title: 'Student Development',
      description: 'Holistic approach to student growth through seminars, workshops, and community activities.'
    },
    {
      icon: BarChart3,
      title: 'Progress Tracking',
      description: 'Monitor academic performance and participation with detailed analytics and reporting.'
    },
    {
      icon: Award,
      title: 'Achievement Recognition',
      description: 'Celebrate student accomplishments with certificates and achievement badges.'
    },
    {
      icon: Shield,
      title: 'Secure Platform',
      description: 'Enterprise-grade security ensuring the safety of all student and institutional data.'
    },
    {
      icon: HelpingHand,
      title: 'Community Support',
      description: 'Building a supportive community where scholars can thrive and help each other grow.'
    },
  ];

  const stats = [
    { number: '500+', label: 'Scholars Supported' },
    { number: '95%', label: 'Graduation Rate' },
    { number: '50+', label: 'Partner Schools' },
    { number: '10', label: 'Years of Service' },
  ];

  // Navigation items for footer
  const navigationItems = [
    { name: 'Home', href: '/' },
    { name: 'About', href: '#about' },
    { name: 'Programs', href: '#programs' },
    { name: 'Scholars', href: '#scholars' },
    { name: 'Contact', href: '#contact' },
  ];

  // Sample parish information - replace with actual parish details
  const parishInfo = {
    name: "Servus Amoris Foundation",
    location: "Nampicuan, Guimba Nueva Ecija",
    contact: "+63 2 1234 5678",
    email: "info@servusamorisfoundation.org.ph",
    massSchedule: [
      { day: "Sunday", times: ["7:00 AM", "9:00 AM", "5:00 PM"] },
      { day: "Weekdays", times: ["6:30 AM", "6:00 PM"] },
      { day: "Saturday", times: ["6:30 AM", "4:00 PM"] }
    ]
  };

  return (
    <div className="min-h-screen bg-white dark:bg-gray-900">
      <Header />
      
      {/* Hero Section with Parish Background */}
      <section className="relative min-h-screen flex items-center justify-center overflow-hidden">
        {/* Background Image - Replace with actual parish image */}
        <div 
          className="absolute inset-0 bg-cover bg-center bg-no-repeat"
          style={{
            backgroundImage: 'url("https://i.pinimg.com/1200x/6d/e0/95/6de0952f1da2ddb41466c56c5f5fec50.jpg")',
          }}
        >
          <div className="absolute inset-0 bg-black/50"></div>
        </div>

        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
          <div className="animate-fadeIn">
            {/* Parish Badge */}
            <div className="inline-flex items-center space-x-2 bg-white/20 backdrop-blur-sm text-white/90 px-6 py-3 rounded-full text-sm font-medium mb-8 border border-white/30">
              <Church className="w-4 h-4" />
              <span>{parishInfo.name}</span>
            </div>
            
            {/* Main Heading */}
            <h1 className="text-4xl md:text-6xl lg:text-7xl font-bold mb-6 leading-tight">
              <span className="bg-gradient-to-r from-white to-amber-200 bg-clip-text text-transparent">
                CROSS
              </span>
            </h1>
            
            {/* Subheading */}
            <p className="text-xl lg:text-2xl mb-8 max-w-3xl mx-auto leading-relaxed text-white/90">
              Care, Renewal, Outreach, Support & Sustainability
              <br />
              <span className="text-lg text-white/80">
                A Scholarship Program of Servus Amoris Foundation
              </span>
            </p>

            {/* CTA Buttons */}
            <div className="flex flex-col sm:flex-row gap-4 justify-center items-center mb-16">
              <Link
                to="/login"
                className="group bg-white text-blue-600 px-8 py-4 rounded-xl 
                         font-semibold text-lg hover:bg-gray-50 transition-all duration-300 
                         hover:scale-105 shadow-lg hover:shadow-xl flex items-center space-x-2"
              >
                <span>Scholar Portal</span>
                <ArrowRight className="w-5 h-5 group-hover:translate-x-1 transition-transform" />
              </Link>
              
              <Link
                to="/programs"
                className="group border-2 border-white text-white px-8 py-4 rounded-xl 
                         font-semibold text-lg hover:bg-white/10 transition-all duration-300 
                         hover:scale-105 flex items-center space-x-2 backdrop-blur-sm"
              >
                <span>Our Programs</span>
              </Link>
            </div>

            {/* Quick Parish Info */}
            <div className="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
              <div className="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                <div className="flex items-center space-x-2 justify-center mb-2">
                  <MapPin className="w-4 h-4" />
                  <span className="font-medium">Location</span>
                </div>
                <p className="text-sm text-white/80">{parishInfo.location}</p>
              </div>
              
              <div className="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                <div className="flex items-center space-x-2 justify-center mb-2">
                  <Phone className="w-4 h-4" />
                  <span className="font-medium">Contact</span>
                </div>
                <p className="text-sm text-white/80">{parishInfo.contact}</p>
              </div>
              
              <div className="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                <div className="flex items-center space-x-2 justify-center mb-2">
                  <Calendar className="w-4 h-4" />
                  <span className="font-medium">Mass Schedule</span>
                </div>
                <p className="text-sm text-white/80">Daily Masses Available</p>
              </div>
            </div>
          </div>
        </div>

        {/* Scroll Indicator */}
        <div className="absolute bottom-8 left-1/2 transform -translate-x-1/2">
          <div className="animate-bounce">
            <ArrowRight className="w-6 h-6 text-white transform rotate-90" />
          </div>
        </div>
      </section>

      {/* About Section */}
      <section id="about" className="py-20 bg-white dark:bg-gray-800">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <div className="inline-flex items-center space-x-2 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 px-4 py-2 rounded-full text-sm font-medium mb-4">
              <Heart className="w-4 h-4" />
              <span>About Our Mission</span>
            </div>
            <h2 className="text-3xl lg:text-5xl font-bold text-gray-900 dark:text-white mb-4">
              Serving Through <span className="text-blue-600 dark:text-blue-400">Education</span>
            </h2>
            <p className="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto leading-relaxed">
              The Servus Amoris Foundation, in partnership with {parishInfo.name}, 
              is dedicated to transforming lives through education. Our CROSS program 
              provides comprehensive support to deserving students, empowering them 
              to achieve their academic dreams.
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            {stats.map((stat, index) => (
              <div key={stat.label} className="text-center">
                <div className="text-3xl lg:text-4xl font-bold text-blue-600 dark:text-blue-400 mb-2">
                  {stat.number}
                </div>
                <div className="text-gray-600 dark:text-gray-400 text-sm lg:text-base">
                  {stat.label}
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Programs Section */}
      <section id="programs" className="py-20 bg-gray-50 dark:bg-gray-900/50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <div className="inline-flex items-center space-x-2 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 px-4 py-2 rounded-full text-sm font-medium mb-4">
              <BookOpen className="w-4 h-4" />
              <span>Our Programs</span>
            </div>
            <h2 className="text-3xl lg:text-5xl font-bold text-gray-900 dark:text-white mb-4">
              Comprehensive <span className="text-amber-600 dark:text-amber-400">Support</span>
            </h2>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {features.map((feature, index) => (
              <div
                key={feature.title}
                className="group bg-white dark:bg-gray-800 rounded-2xl p-8 
                         border border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-700
                         transition-all duration-500 hover:scale-105 shadow-lg hover:shadow-xl"
              >
                <div className="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl 
                              flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                  <feature.icon className="w-7 h-7 text-white" />
                </div>
                <h3 className="text-xl font-bold text-gray-900 dark:text-white mb-3">
                  {feature.title}
                </h3>
                <p className="text-gray-600 dark:text-gray-300 leading-relaxed">
                  {feature.description}
                </p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Contact Section */}
      <section id="contact" className="py-20 bg-white dark:bg-gray-800">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <h2 className="text-3xl lg:text-5xl font-bold text-gray-900 dark:text-white mb-4">
              Get In <span className="text-blue-600 dark:text-blue-400">Touch</span>
            </h2>
            <p className="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
              We'd love to hear from you. Contact us for more information about our scholarship programs.
            </p>
          </div>

          <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 max-w-6xl mx-auto">
            {/* Contact Information */}
            <div className="space-y-6">
              <h3 className="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                Contact Information
              </h3>
              
              <div className="space-y-4">
                <div className="flex items-center space-x-4">
                  <div className="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                    <Church className="w-6 h-6 text-blue-600 dark:text-blue-400" />
                  </div>
                  <div>
                    <p className="font-semibold text-gray-900 dark:text-white">{parishInfo.name}</p>
                    <p className="text-gray-600 dark:text-gray-400">{parishInfo.location}</p>
                  </div>
                </div>

                <div className="flex items-center space-x-4">
                  <div className="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                    <Phone className="w-6 h-6 text-green-600 dark:text-green-400" />
                  </div>
                  <div>
                    <p className="font-semibold text-gray-900 dark:text-white">Phone</p>
                    <p className="text-gray-600 dark:text-gray-400">{parishInfo.contact}</p>
                  </div>
                </div>

                <div className="flex items-center space-x-4">
                  <div className="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                    <Mail className="w-6 h-6 text-amber-600 dark:text-amber-400" />
                  </div>
                  <div>
                    <p className="font-semibold text-gray-900 dark:text-white">Email</p>
                    <p className="text-gray-600 dark:text-gray-400">{parishInfo.email}</p>
                  </div>
                </div>
              </div>

              {/* Mass Schedule */}
              <div className="mt-8">
                <h4 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">Mass Schedule</h4>
                <div className="space-y-2">
                  {parishInfo.massSchedule.map((schedule, index) => (
                    <div key={index} className="flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-700">
                      <span className="text-gray-700 dark:text-gray-300">{schedule.day}</span>
                      <span className="text-gray-900 dark:text-white font-medium">
                        {schedule.times.join(', ')}
                      </span>
                    </div>
                  ))}
                </div>
              </div>
            </div>

            {/* Contact Form */}
            <div className="bg-gray-50 dark:bg-gray-900/50 rounded-2xl p-8">
              <h3 className="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                Send us a Message
              </h3>
              <form className="space-y-6">
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                      First Name
                    </label>
                    <input
                      type="text"
                      className="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                      placeholder="Your first name"
                    />
                  </div>
                  <div>
                    <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                      Last Name
                    </label>
                    <input
                      type="text"
                      className="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                      placeholder="Your last name"
                    />
                  </div>
                </div>
                <div>
                  <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Email Address
                  </label>
                  <input
                    type="email"
                    className="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="your.email@example.com"
                  />
                </div>
                <div>
                  <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Message
                  </label>
                  <textarea
                    rows={4}
                    className="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Your message..."
                  ></textarea>
                </div>
                <button
                  type="submit"
                  className="w-full bg-gradient-to-r from-blue-500 to-blue-700 text-white py-3 px-4 rounded-lg font-semibold hover:opacity-90 transition-all duration-200"
                >
                  Send Message
                </button>
              </form>
            </div>
          </div>
        </div>
      </section>

      {/* Footer */}
      <footer className="bg-gray-900 text-white py-12">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div className="col-span-1 md:col-span-2">
              <div className="flex items-center space-x-3 mb-4">
                <div className="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl flex items-center justify-center">
                  <GraduationCap className="w-6 h-6 text-white" />
                </div>
                <div>
                  <h3 className="text-xl font-bold">CROSS</h3>
                  <p className="text-gray-400 text-sm">Servus Amoris Foundation</p>
                </div>
              </div>
              <p className="text-gray-400 mb-4">
                Transforming lives through education and compassion. Our scholarship program 
                empowers deserving students to achieve their academic dreams.
              </p>
            </div>
            
            <div>
              <h4 className="font-semibold mb-4">Quick Links</h4>
              <div className="space-y-2">
                {navigationItems.map((item) => (
                  <a
                    key={item.name}
                    href={item.href}
                    className="block text-gray-400 hover:text-white transition-colors"
                  >
                    {item.name}
                  </a>
                ))}
              </div>
            </div>
            
            <div>
              <h4 className="font-semibold mb-4">Connect</h4>
              <div className="space-y-2 text-gray-400">
                <p>{parishInfo.location}</p>
                <p>{parishInfo.contact}</p>
                <p>{parishInfo.email}</p>
              </div>
            </div>
          </div>
          
          <div className="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
            <p>&copy; 2024 Servus Amoris Foundation. All rights reserved.</p>
          </div>
        </div>
      </footer>
    </div>
  );
};