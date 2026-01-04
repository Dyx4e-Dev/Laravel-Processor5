# Quiz System - Quick Troubleshooting Guide

## 🎯 Quick Test

1. Open browser DevTools (F12)
2. Go to Console tab
3. Scroll to bottom of page and submit quiz form
4. Look for "Quiz system initialized successfully" message

---

## ❌ If Quiz Popup Still Doesn't Appear

### Step 1: Check Console Errors
```
F12 → Console Tab → Look for RED error messages
```

### Step 2: Check if Elements Exist
Run in Console:
```javascript
document.getElementById('quiz-popup')
document.getElementById('quiz-form-container')
document.querySelector('.quiz-form')
```
All should return DOM elements (not null)

### Step 3: Test Popup Manually
Run in Console:
```javascript
const popup = document.getElementById('quiz-popup');
popup.classList.add('show');
popup.style.display = 'flex';
```
If popup appears, JavaScript initialization is the issue.

---

## ❌ If Quiz Popup Appears But No Questions

### Check JavaScript Logs
In Console, look for:
- ✅ "Starting quiz with X questions"
- ❌ If you see "Starting quiz with 0 questions" → Database issue

### Test Quiz Data
Run in Console:
```javascript
console.log(rawQuizzesData)
```
Should show array of quiz objects with: `question`, `option_a`, `option_b`, `option_c`, `option_d`, `answer`

### If Empty Array
1. Check [app/Http/Controllers/FrontendController.php](../app/Http/Controllers/FrontendController.php#L21)
2. Verify `Quiz::inRandomOrder()->limit(10)->get()` returns data
3. Check if Quiz table has data in database

---

## ❌ If Form Won't Submit

### Check Browser Console
Look for validation errors:
```javascript
"Form data valid, showing popup"
```

### Verify Form Inputs
Run in Console:
```javascript
document.getElementById('nama').value
document.getElementById('email').value
document.getElementById('who-explain').value
```
All should have values (not empty)

### Check CSRF Token
Run in Console:
```javascript
document.querySelector('input[name="_token"]').value
```
Should return a long token string

---

## ❌ If Quiz Saves But No Data in Database

### Check Network Request
1. F12 → Network tab
2. Filter: XHR/Fetch
3. Submit quiz
4. Look for POST request to `/submit-quiz`
5. Check Response tab for success message

### Verify API Response
Should see:
```json
{
  "success": true,
  "message": "Data quiz berhasil disimpan!",
  "data": {...}
}
```

### If Error Response
Check error message and verify:
- ✅ Email is valid format
- ✅ Team ID exists in database
- ✅ Score is between 0-10

---

## ❌ If Timer Doesn't Count Down

### Check Console
Look for timer updates: `"10s"`, `"9s"`, `"8s"`, etc.

### Manually Test Timer
Run in Console after quiz starts:
```javascript
setInterval(() => console.log('tick'), 1000)
```
Should see "tick" every second

---

## ✅ Success Indicators

All of these should appear in console when quiz works:

```
✓ Quiz system initialized successfully
✓ Quiz form submitted
✓ Form data valid, showing popup
✓ Showing quiz popup
✓ Starting quiz with 10 questions
✓ [Questions load and display]
✓ [As you answer] handleAnswer called
✓ Saving to database: {nama, email, who_explain, score, _token}
✓ Successfully saved: {success: true, message: "...", data: {...}}
✓ Close quiz button clicked
✓ Hiding quiz popup
```

---

## 🔍 Database Verification

Check if quiz data saved:
```sql
SELECT * FROM quiz_results ORDER BY created_at DESC LIMIT 5;
```

Should show recent quiz attempts with:
- nama
- email
- team_id
- score
- reward_status

---

## 📝 Log All Activity

Enable detailed logging by uncommenting lines in [public/js/script.js](../public/js/script.js#L798):

All `console.log()` calls provide detailed debugging information.

---

## 🆘 Still Not Working?

1. **Clear Cache**
   ```
   CTRL + SHIFT + DEL → Clear all
   Then reload page
   ```

2. **Check File Permissions**
   - Ensure [public/js/script.js](../public/js/script.js) is readable
   - Ensure [resources/views/frontend/quiz.blade.php](../resources/views/frontend/quiz.blade.php) is readable

3. **Verify Laravel Routes**
   ```php
   Route::post('/submit-quiz', [FrontendController::class, 'submitQuiz']);
   ```
   Should exist in [routes/web.php](../routes/web.php)

4. **Check Database Connection**
   ```php
   php artisan tinker
   >>> App\Models\Quiz::count()
   ```
   Should return number > 0

5. **Test Backend Directly**
   ```
   POST http://localhost/submit-quiz
   Body: {nama: "Test", email: "test@test.com", who_explain: 1, score: 5}
   ```

---

## 📧 Contact Info

If all troubleshooting steps fail:
- Check Laravel logs: `storage/logs/laravel.log`
- Check server error logs: `php_errors.log`
- Verify PHP version compatibility
- Check database connection config

---

**Last Updated:** January 4, 2026
