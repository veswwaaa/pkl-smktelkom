// Dashboard Siswa JavaScript

document.addEventListener("DOMContentLoaded", function () {
    // Highlight active menu
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll(".nav-link");

    navLinks.forEach((link) => {
        if (link.getAttribute("href") === currentPath) {
            link.classList.add("active");
        } else {
            link.classList.remove("active");
        }
    });

    // Add animation to cards on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: "0px 0px -100px 0px",
    };

    const observer = new IntersectionObserver(function (entries) {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = "0";
                entry.target.style.transform = "translateY(20px)";

                setTimeout(() => {
                    entry.target.style.transition = "all 0.6s ease";
                    entry.target.style.opacity = "1";
                    entry.target.style.transform = "translateY(0)";
                }, 100);

                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe all cards
    const cards = document.querySelectorAll(".card");
    cards.forEach((card) => {
        observer.observe(card);
    });

    // Quick action button ripple effect
    const quickActionBtns = document.querySelectorAll(".quick-action-btn");
    quickActionBtns.forEach((btn) => {
        btn.addEventListener("click", function (e) {
            const ripple = document.createElement("span");
            ripple.classList.add("ripple");
            this.appendChild(ripple);

            const x = e.clientX - this.offsetLeft;
            const y = e.clientY - this.offsetTop;

            ripple.style.left = x + "px";
            ripple.style.top = y + "px";

            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
});

// Logout confirmation
const logoutLink = document.querySelector('a[href="/logout"]');
if (logoutLink) {
    logoutLink.addEventListener("click", function (e) {
        e.preventDefault();

        if (confirm("Apakah Anda yakin ingin logout?")) {
            window.location.href = "/logout";
        }
    });
}
