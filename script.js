// Mobile Navigation Toggle
document.addEventListener('DOMContentLoaded', function() {
  const hamburger = document.querySelector('.hamburger');
  const navLinks = document.querySelector('.nav-links');
  const userMenu = document.querySelector('.user-menu');
  
  if (hamburger) {
    hamburger.addEventListener('click', function() {
      navLinks.classList.toggle('active');
      userMenu.classList.toggle('active');
      hamburger.classList.toggle('active');
    });
  }
  
  // Form Tab Switching
  const formTabs = document.querySelectorAll('.form-tab');
  if (formTabs.length > 0) {
    formTabs.forEach(tab => {
      tab.addEventListener('click', function() {
        const target = this.getAttribute('data-target');
        
        // Remove active class from all tabs and contents
        formTabs.forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        
        // Add active class to current tab and content
        this.classList.add('active');
        document.getElementById(target).classList.add('active');
      });
    });
  }
  
  // Fade in animation on page load
  const fadeElements = document.querySelectorAll('.fade-in');
  fadeElements.forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(20px)';
    el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
  });
  
  setTimeout(() => {
    fadeElements.forEach(el => {
      el.style.opacity = '1';
      el.style.transform = 'translateY(0)';
    });
  }, 100);
});

// Form Validation
function validateForm(formId) {
  const form = document.getElementById(formId);
  const inputs = form.querySelectorAll('input[required], select[required]');
  let isValid = true;
  
  inputs.forEach(input => {
    if (!input.value.trim()) {
      input.style.borderColor = 'red';
      isValid = false;
    } else {
      input.style.borderColor = '#ddd';
    }
  });
  
  return isValid;
}

// Password Strength Check
function checkPasswordStrength(password) {
  const strength = {
    0: "Very Weak",
    1: "Weak",
    2: "Medium",
    3: "Strong",
    4: "Very Strong"
  };
  
  let score = 0;
  
  // Check password length
  if (password.length >= 8) score++;
  if (password.length >= 12) score++;
  
  // Check for mixed case
  if (password.match(/[a-z]/) && password.match(/[A-Z]/)) score++;
  
  // Check for numbers and special characters
  if (password.match(/\d/)) score++;
  if (password.match(/[^a-zA-Z\d]/)) score++;
  
  return {
    score: score,
    text: strength[score]
  };
}