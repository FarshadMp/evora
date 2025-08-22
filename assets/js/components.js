// Component Loader for Header and Footer
document.addEventListener('DOMContentLoaded', function() {
    // Load Header
    loadComponent('header', 'includes/header.html');
    
    // Load Footer
    loadComponent('footer', 'includes/footer.html');
});

function loadComponent(elementId, filePath) {
    fetch(filePath)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(html => {
            const element = document.getElementById(elementId);
            if (element) {
                element.innerHTML = html;
                
                // Re-initialize any scripts that need to run after component load
                if (elementId === 'header') {
                    initializeHeaderScripts();
                }
            }
        })
        .catch(error => {
            console.error('Error loading component:', error);
        });
}

function initializeHeaderScripts() {
    // Re-initialize mobile menu functionality
    const mobileMenuBtn = document.getElementById("mobile-menu-btn");
    const mobileMenu = document.getElementById("mobile-menu");
    const mobileMenuClose = document.getElementById("mobile-menu-close");
    const mobileMenuOverlay = mobileMenu?.querySelector(".bg-black");

    if (mobileMenuBtn && mobileMenu && mobileMenuClose) {
        // Open mobile menu
        mobileMenuBtn.addEventListener("click", function () {
            mobileMenu.classList.remove("hidden");
            // Trigger reflow to enable transition
            mobileMenu.offsetHeight;
            mobileMenu
                .querySelector(".transform")
                .classList.remove("-translate-x-full");
        });

        // Close mobile menu
        function closeMobileMenu() {
            mobileMenu.querySelector(".transform").classList.add("-translate-x-full");
            setTimeout(() => {
                mobileMenu.classList.add("hidden");
            }, 300);
        }

        mobileMenuClose.addEventListener("click", closeMobileMenu);

        // Close on overlay click
        if (mobileMenuOverlay) {
            mobileMenuOverlay.addEventListener("click", closeMobileMenu);
        }

        // Close on escape key
        document.addEventListener("keydown", function (e) {
            if (e.key === "Escape" && !mobileMenu.classList.contains("hidden")) {
                closeMobileMenu();
            }
        });
    }
}
