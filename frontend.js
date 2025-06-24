/**
 * Frontend JS for One Paper MCQS Maker
 * Author: Shahid Hussain Soomro
 * GitHub: https://github.com/shahidhussainsoomro/MCQS-Manager
 */

document.addEventListener("DOMContentLoaded", () => {
  // Show popup with correct answer
  const popup = document.getElementById("correct-answer-popup");
  if (popup) {
    popup.style.display = "block";
    document.getElementById("close-popup")?.addEventListener("click", () => {
      popup.style.display = "none";
    });
  }

  // Timer countdown logic (if timer exists)
  const timer = document.getElementById("exam-timer");
  if (timer) {
    let duration = parseInt(timer.dataset.duration); // in seconds
    const countdown = setInterval(() => {
      const minutes = Math.floor(duration / 60);
      const seconds = duration % 60;
      timer.textContent = `${minutes}:${seconds < 10 ? "0" : ""}${seconds}`;
      duration--;
      if (duration < 0) {
        clearInterval(countdown);
        alert("⏰ Time's up! Submitting exam...");
        document.getElementById("exam-form")?.submit();
      }
    }, 1000);
  }
});
