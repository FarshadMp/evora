// ÉVORA Website JavaScript

// Tailwind CSS Configuration
tailwind.config = {
  theme: {
    extend: {
      colors: {
        "evora-beige": "#f8f6f0",
        "evora-brown": "#8B4513",
        "evora-dark": "#2c2c2c",
        "evora-gold": "#D4AF37",
        primary: "#231F20",
      },
      fontFamily: {
        manrope: ["Manrope", "Arial", "sans-serif"],
        sans: ["Manrope", "Arial", "sans-serif"],
      },
    },
  },
};

// Add smooth scrolling for navigation links
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
  anchor.addEventListener("click", function (e) {
    e.preventDefault();
    document.querySelector(this.getAttribute("href")).scrollIntoView({
      behavior: "smooth",
    });
  });
});

// Product scroll navigation
document.addEventListener("DOMContentLoaded", function () {
  const scrollContainer = document.querySelector(".product-scroll");
  const scrollLeftBtn = document.getElementById("scrollLeft");
  const scrollRightBtn = document.getElementById("scrollRight");

  if (scrollContainer && scrollLeftBtn && scrollRightBtn) {
    // Scroll left
    scrollLeftBtn.addEventListener("click", function () {
      scrollContainer.scrollBy({
        left: -300,
        behavior: "smooth",
      });
    });

    // Scroll right
    scrollRightBtn.addEventListener("click", function () {
      scrollContainer.scrollBy({
        left: 300,
        behavior: "smooth",
      });
    });

    // Show/hide arrows based on scroll position
    scrollContainer.addEventListener("scroll", function () {
      const isAtStart = scrollContainer.scrollLeft === 0;
      const isAtEnd =
        scrollContainer.scrollLeft + scrollContainer.clientWidth >=
        scrollContainer.scrollWidth;

      scrollLeftBtn.style.opacity = isAtStart ? "0" : "1";
      scrollRightBtn.style.opacity = isAtEnd ? "0" : "1";
    });

    // Initial arrow visibility
    scrollRightBtn.style.opacity = "1";
  }

  // New Arrivals scroll navigation
  const scrollContainerNew = document.querySelector(".product-scroll-new");
  const scrollLeftBtnNew = document.getElementById("scrollLeftNew");
  const scrollRightBtnNew = document.getElementById("scrollRightNew");

  if (scrollContainerNew && scrollLeftBtnNew && scrollRightBtnNew) {
    // Scroll left
    scrollLeftBtnNew.addEventListener("click", function () {
      scrollContainerNew.scrollBy({
        left: -300,
        behavior: "smooth",
      });
    });

    // Scroll right
    scrollRightBtnNew.addEventListener("click", function () {
      scrollContainerNew.scrollBy({
        left: 300,
        behavior: "smooth",
      });
    });

    // Show/hide arrows based on scroll position
    scrollContainerNew.addEventListener("scroll", function () {
      const isAtStart = scrollContainerNew.scrollLeft === 0;
      const isAtEnd =
        scrollContainerNew.scrollLeft + scrollContainerNew.clientWidth >=
        scrollContainerNew.scrollWidth;

      scrollLeftBtnNew.style.opacity = isAtStart ? "0" : "1";
      scrollRightBtnNew.style.opacity = isAtEnd ? "0" : "1";
    });

    // Initial arrow visibility
    scrollRightBtnNew.style.opacity = "1";
  }

  // Bestsellers scroll navigation
  const scrollContainerBestsellers = document.querySelector(
    ".bestsellers-scroll"
  );
  const scrollLeftBtnBestsellers = document.getElementById(
    "scrollLeftBestsellers"
  );
  const scrollRightBtnBestsellers = document.getElementById(
    "scrollRightBestsellers"
  );

  if (
    scrollContainerBestsellers &&
    scrollLeftBtnBestsellers &&
    scrollRightBtnBestsellers
  ) {
    // Scroll left
    scrollLeftBtnBestsellers.addEventListener("click", function () {
      scrollContainerBestsellers.scrollBy({
        left: -300,
        behavior: "smooth",
      });
    });

    // Scroll right
    scrollRightBtnBestsellers.addEventListener("click", function () {
      scrollContainerBestsellers.scrollBy({
        left: 300,
        behavior: "smooth",
      });
    });

    // Show/hide arrows based on scroll position
    scrollContainerBestsellers.addEventListener("scroll", function () {
      const isAtStart = scrollContainerBestsellers.scrollLeft === 0;
      const isAtEnd =
        scrollContainerBestsellers.scrollLeft +
          scrollContainerBestsellers.clientWidth >=
        scrollContainerBestsellers.scrollWidth;

      scrollLeftBtnBestsellers.style.opacity = isAtStart ? "0" : "1";
      scrollRightBtnBestsellers.style.opacity = isAtEnd ? "0" : "1";
    });

    // Initial arrow visibility
    scrollRightBtnBestsellers.style.opacity = "1";
  }

  // Product image hover functionality with smooth transitions like Rocksbox
  const productContainers = document.querySelectorAll(
    ".relative.overflow-hidden.mb-4"
  );
  productContainers.forEach(function (container) {
    const mainImage = container.querySelector(".product-image-main");
    const hoverImage = container.querySelector(".product-image-hover");

    if (mainImage && hoverImage) {
      // Show hover image on mouse enter
      container.addEventListener("mouseenter", function () {
        hoverImage.style.opacity = "1";
      });

      // Hide hover image on mouse leave
      container.addEventListener("mouseleave", function () {
        hoverImage.style.opacity = "0";
      });
    }
  });

  // Bestsellers scroll arrow navigation - Like Rocksbox
  const bestsellersContainer = document.querySelector(
    ".bestsellers-scroll-container"
  );
  const bestsellersScroll = document.getElementById("bestsellersScroll");
  const leftArrow = document.getElementById("scrollLeftNew");
  const rightArrow = document.getElementById("scrollRightNew");

  console.log("Bestsellers elements found:", {
    container: bestsellersContainer,
    scroll: bestsellersScroll,
    leftArrow: leftArrow,
    rightArrow: rightArrow,
  });

  if (bestsellersContainer && bestsellersScroll && leftArrow && rightArrow) {
    // Scroll left
    leftArrow.addEventListener("click", function (e) {
      e.preventDefault();
      console.log("Left arrow clicked");
      bestsellersScroll.scrollBy({
        left: -320, // Scroll by one product width + gap
        behavior: "smooth",
      });
    });

    // Scroll right
    rightArrow.addEventListener("click", function (e) {
      e.preventDefault();
      console.log("Right arrow clicked");
      bestsellersScroll.scrollBy({
        left: 320, // Scroll by one product width + gap
        behavior: "smooth",
      });
    });

    // Update arrow states based on scroll position
    function updateArrowStates() {
      const isAtStart = bestsellersScroll.scrollLeft <= 0;
      const isAtEnd =
        bestsellersScroll.scrollLeft + bestsellersScroll.clientWidth >=
        bestsellersScroll.scrollWidth - 5;

      console.log("Scroll position:", {
        scrollLeft: bestsellersScroll.scrollLeft,
        clientWidth: bestsellersScroll.clientWidth,
        scrollWidth: bestsellersScroll.scrollWidth,
        isAtStart: isAtStart,
        isAtEnd: isAtEnd,
      });

      // Show/hide arrows based on scroll position
      leftArrow.style.opacity = isAtStart ? "0" : "1";
      rightArrow.style.opacity = isAtEnd ? "0" : "1";

      // Disable/enable arrows
      leftArrow.disabled = isAtStart;
      rightArrow.disabled = isAtEnd;
    }

    // Listen for scroll events
    bestsellersScroll.addEventListener("scroll", updateArrowStates);

    // Initial state
    updateArrowStates();

    // Update states after a short delay to ensure proper initialization
    setTimeout(updateArrowStates, 1000);
  } else {
    console.log("Some elements not found for bestsellers scroll");
  }

  // Best Sellers Carousel Scroll Progress and Navigation
  const bestsellerCarousel = document.getElementById("bestseller-carousel");
  const scrollProgress = document.getElementById("scroll-progress");
  const bestsellerLeft = document.getElementById("bestseller-left");
  const bestsellerRight = document.getElementById("bestseller-right");

  if (bestsellerCarousel && scrollProgress) {
    // Scroll progress functionality
    bestsellerCarousel.addEventListener("scroll", function () {
      const scrollLeft = bestsellerCarousel.scrollLeft;
      const scrollWidth =
        bestsellerCarousel.scrollWidth - bestsellerCarousel.clientWidth;
      const progress = (scrollLeft / scrollWidth) * 100;

      scrollProgress.style.width = progress + "%";

      // Update arrow visibility
      updateBestsellerArrows();
    });

    // Arrow navigation functionality
    if (bestsellerLeft && bestsellerRight) {
      bestsellerLeft.addEventListener("click", function () {
        bestsellerCarousel.scrollBy({
          left: -320, // Scroll by one product width + gap
          behavior: "smooth",
        });
      });

      bestsellerRight.addEventListener("click", function () {
        bestsellerCarousel.scrollBy({
          left: 320, // Scroll by one product width + gap
          behavior: "smooth",
        });
      });

      // Function to update arrow visibility
      function updateBestsellerArrows() {
        const isAtStart = bestsellerCarousel.scrollLeft <= 0;
        const isAtEnd =
          bestsellerCarousel.scrollLeft + bestsellerCarousel.clientWidth >=
          bestsellerCarousel.scrollWidth - 5;

        bestsellerLeft.style.opacity = isAtStart ? "0" : "1";
        bestsellerRight.style.opacity = isAtEnd ? "0" : "1";
      }

      // Initial arrow state
      updateBestsellerArrows();
    }
  }

  // New Arrivals Carousel Scroll Progress and Navigation
  const newArrivalsCarousel = document.getElementById("newarrivals-carousel");
  const newArrivalsScrollProgress = document.getElementById(
    "newarrivals-scroll-progress"
  );
  const newArrivalsLeft = document.getElementById("newarrivals-left");
  const newArrivalsRight = document.getElementById("newarrivals-right");

  if (newArrivalsCarousel && newArrivalsScrollProgress) {
    // Scroll progress functionality
    newArrivalsCarousel.addEventListener("scroll", function () {
      const scrollLeft = newArrivalsCarousel.scrollLeft;
      const scrollWidth =
        newArrivalsCarousel.scrollWidth - newArrivalsCarousel.clientWidth;
      const progress = (scrollLeft / scrollWidth) * 100;

      newArrivalsScrollProgress.style.width = progress + "%";

      // Update arrow visibility
      updateNewArrivalsArrows();
    });

    // Arrow navigation functionality
    if (newArrivalsLeft && newArrivalsRight) {
      newArrivalsLeft.addEventListener("click", function () {
        newArrivalsCarousel.scrollBy({
          left: -320, // Scroll by one product width + gap
          behavior: "smooth",
        });
      });

      newArrivalsRight.addEventListener("click", function () {
        newArrivalsCarousel.scrollBy({
          left: 320, // Scroll by one product width + gap
          behavior: "smooth",
        });
      });

      // Function to update arrow visibility
      function updateNewArrivalsArrows() {
        const isAtStart = newArrivalsCarousel.scrollLeft <= 0;
        const isAtEnd =
          newArrivalsCarousel.scrollLeft + newArrivalsCarousel.clientWidth >=
          newArrivalsCarousel.scrollWidth - 5;

        newArrivalsLeft.style.opacity = isAtStart ? "0" : "1";
        newArrivalsRight.style.opacity = isAtEnd ? "0" : "1";
      }

      // Initial arrow state
      updateNewArrivalsArrows();
    }
  }

  // Reviews Carousel - Manual scroll only (CSS handles auto-scroll)
  const reviewsCarousel = document.getElementById("reviews-carousel");

  if (reviewsCarousel) {
    console.log("Reviews carousel found:", reviewsCarousel);

    // Pause CSS animation on user interaction
    reviewsCarousel.addEventListener("mouseenter", () => {
      reviewsCarousel.style.animationPlayState = "paused";
    });

    reviewsCarousel.addEventListener("mouseleave", () => {
      setTimeout(() => {
        reviewsCarousel.style.animationPlayState = "running";
      }, 1000);
    });

    // Pause on touch/scroll
    reviewsCarousel.addEventListener("touchstart", () => {
      reviewsCarousel.style.animationPlayState = "paused";
    });

    reviewsCarousel.addEventListener("scroll", () => {
      reviewsCarousel.style.animationPlayState = "paused";
      setTimeout(() => {
        reviewsCarousel.style.animationPlayState = "running";
      }, 2000);
    });

    // Pause on wheel
    reviewsCarousel.addEventListener(
      "wheel",
      (e) => {
        e.preventDefault();
        reviewsCarousel.style.animationPlayState = "paused";
        setTimeout(() => {
          reviewsCarousel.style.animationPlayState = "running";
        }, 2000);
      },
      { passive: false }
    );
  } else {
    console.log("Reviews carousel not found!");
  }

  // Mobile Menu Functionality
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

  // Product Image Gallery Functionality
  const mainImage = document.getElementById("main-image");
  const thumbnailImages = document.querySelectorAll(".thumbnail-image");

  if (mainImage && thumbnailImages.length > 0) {
    thumbnailImages.forEach((thumbnail) => {
      thumbnail.addEventListener("click", function () {
        const newImageSrc = this.getAttribute("data-image");

        // Update main image with fade effect
        mainImage.style.opacity = "0";
        setTimeout(() => {
          mainImage.src = newImageSrc;
          mainImage.style.opacity = "1";
        }, 150);

        // Update thumbnail borders
        thumbnailImages.forEach((thumb) => {
          thumb.classList.remove("border-evora-brown");
          thumb.classList.add("border-transparent");
        });
        this.classList.remove("border-transparent");
        this.classList.add("border-evora-brown");
      });
    });
  }
});
