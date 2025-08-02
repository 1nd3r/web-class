@echo off
echo Committing Sprint 6 JavaScript Validation Changes...

git add .
git commit -m "Sprint 6: Implement JavaScript form validation

- Add comprehensive email validation with RFC compliance
- Implement date range validation for rental periods  
- Create human name validation with international support
- Add real-time validation with visual feedback
- Create interactive validation demo page
- Enhance CSS with error/success states
- Update all forms with JavaScript integration

Sprint 6 Requirements Complete:
✅ Email address structure validation
✅ Date range validation  
✅ Human name with acceptable characters

Files Added/Modified:
- js/validation.js (565 lines) - Main validation system
- validation-demo.html (325 lines) - Interactive demo
- css/style.css - Added validation styling
- booking.html, contact.html, index.html - JavaScript integration"

echo.
echo Sprint 6 committed successfully!
echo.
pause 