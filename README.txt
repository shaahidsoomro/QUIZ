# PakStudy Quiz Manager – Final Version

**Plugin Name:** pakstudy-quiz-manager  
**Author:** Shahid Hussain Soomro  
**Website:** https://pakstudy.xyz  

---

## 🎯 Features

| Feature                | Description                                                                 |
|------------------------|-----------------------------------------------------------------------------|
| MCQ Post Type          | Add quizzes with 4 options, 1 correct, difficulty, explanation              |
| Quiz Shortcode         | Embed on any post/page: [pakstudy_quiz limit="5"]                          |
| Quiz Result Control    | Only logged-in users can view correct answers + explanations                |
| Admin MCQ Dashboard    | View, add, edit, import/export MCQs from backend                           |
| Analytics Dashboard    | View total MCQs, attempts, score %, and visual charts via Chart.js         |
| Attempt Logs           | Logs every user attempt (user, question, option, correct, timestamp)       |
| Leaderboard Shortcode  | [pakstudy_leaderboard range="weekly" limit="10"] shows top scorers         |
| Certificate System     | Auto-generates TCPDF certificate for users with ≥ 80 correct answers       |
| Email Notification     | Sends certificate notification via email when user qualifies               |

---

## 🔌 Installation

1. Upload the plugin folder via WordPress admin or unzip in `/wp-content/plugins/`
2. Activate the plugin via **Plugins > Installed Plugins**
3. Visit **MCQs > MCQ Dashboard** to manage questions
4. Visit **MCQs > MCQ Analytics** to view score data

---

## 📝 Adding Questions

- Go to **MCQs > Add New**
- Fill:
  - Title
  - Question Text
  - Options A–D
  - Correct Option (a/b/c/d)
  - Difficulty: Easy/Medium/Hard
  - Explanation

---

## 🧪 Display Quiz

Use this shortcode on any page or post:

```
[pakstudy_quiz limit="5"]
```

- Only logged-in users can view results
- Score summary and retry button shown

---

## 📈 View Analytics

Visit **MCQs > MCQ Analytics** to see:

- Bar chart with total MCQs, attempts, correct answers, and average score
- Interactive visual using Chart.js

---

## 📋 View Attempts

Go to **MCQs > Attempt Logs** to view all quiz attempt history:
- User, MCQ ID, answer given, whether correct, time

---

## 🏆 Leaderboard

Embed using:

```
[pakstudy_leaderboard range="monthly" limit="10"]
```

- Displays top scorers
- Users with ≥ 80 correct answers see ✔ “Available” under Certificate column

---

## 🎓 Certificates

- When a user scores ≥ 80 correct answers total, a certificate is:
  - 📧 Emailed to them
  - 📄 Available for download via AJAX using TCPDF

---

## ⚙ Required

- PHP 7.4+
- WordPress 6.x
- **TCPDF library** in `/libs/tcpdf/` (create this folder manually and place TCPDF files there)

---

## 📩 Support

Email: shahid@pakstudy.xyz  
Website: https://pakstudy.xyz  
Plugin: Designed by Shahid Hussain Soomro  
