// Navigation scroll effect
const nav = document.querySelector(".nav")
const mobileToggle = document.querySelector(".mobile-toggle")
const navLinks = document.querySelector(".nav-links")
const themeToggle = document.getElementById("themeToggle")

window.addEventListener("scroll", () => {
  if (window.scrollY > 50) {
    nav.classList.add("scrolled")
  } else {
    nav.classList.remove("scrolled")
  }
})

// Mobile menu toggle
mobileToggle.addEventListener("click", () => {
  mobileToggle.classList.toggle("active")
  navLinks.classList.toggle("active")
})

// Theme toggle: apply and persist theme
function applyTheme(theme) {
  if (theme === "dark") {
    document.body.setAttribute("data-theme", "dark")
    if (themeToggle) themeToggle.textContent = "🌙"
  } else {
    document.body.removeAttribute("data-theme")
    if (themeToggle) themeToggle.textContent = "☀️"
  }
}

try {
  const saved = localStorage.getItem("preferredTheme")
  const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches
  const initial = saved || (prefersDark ? 'dark' : 'light')
  applyTheme(initial)

  if (themeToggle) {
    themeToggle.addEventListener('click', () => {
      const current = document.body.getAttribute('data-theme') === 'dark' ? 'dark' : 'light'
      const next = current === 'dark' ? 'light' : 'dark'
      applyTheme(next)
      localStorage.setItem('preferredTheme', next)
    })
  }
} catch (err) {
  console.warn('Theme init error', err)
}

// Close mobile menu when clicking a link
document.querySelectorAll(".nav-links a").forEach((link) => {
  link.addEventListener("click", () => {
    mobileToggle.classList.remove("active")
    navLinks.classList.remove("active")
  })
})

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
  anchor.addEventListener("click", function (e) {
    e.preventDefault()
    const target = document.querySelector(this.getAttribute("href"))
    if (target) {
      const offsetTop = target.offsetTop - 80
      window.scrollTo({
        top: offsetTop,
        behavior: "smooth",
      })
    }
  })
})

// Intersection Observer for fade-in animations
const observerOptions = {
  threshold: 0.1,
  rootMargin: "0px 0px -100px 0px",
}

const observer = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      entry.target.classList.add("visible")
    }
  })
}, observerOptions)

// Observe all project cards and craft cards
document.querySelectorAll(".project-card, .craft-card").forEach((card) => {
  observer.observe(card)
})

// Project filtering
const filterButtons = document.querySelectorAll(".filter-btn")
const projectCards = document.querySelectorAll(".project-card")

filterButtons.forEach((button) => {
  button.addEventListener("click", () => {
    const filter = button.getAttribute("data-filter")

    // Update active button
    filterButtons.forEach((btn) => btn.classList.remove("active"))
    button.classList.add("active")

    // Filter projects
    projectCards.forEach((card) => {
      const category = card.getAttribute("data-category")

      if (filter === "all" || category === filter) {
        card.style.display = "block"
        setTimeout(() => {
          card.classList.add("visible")
        }, 10)
      } else {
        card.classList.remove("visible")
        setTimeout(() => {
          card.style.display = "none"
        }, 300)
      }
    })
  })
})

// Greeting based on time of day and current language
function updateGreeting() {
  const greetingElement = document.querySelector(".greeting")
  const hour = new Date().getHours()
  const currentLang = localStorage.getItem("preferredLanguage") || "sq"

  const greetings = {
    sq: hour < 12 ? "Mirëmëngjes" : hour < 18 ? "Mirëdita" : "Mirëmbrëma",
    en: hour < 12 ? "Good morning" : hour < 18 ? "Good afternoon" : "Good evening",
    de: hour < 12 ? "Guten Morgen" : hour < 18 ? "Guten Tag" : "Guten Abend",
    es: hour < 12 ? "Buenos días" : hour < 18 ? "Buenas tardes" : "Buenas noches",
    fr: hour < 12 ? "Bonjour" : hour < 18 ? "Bon après-midi" : "Bonsoir",
    zh: hour < 12 ? "早上好" : hour < 18 ? "下午好" : "晚上好",
    ar: hour < 12 ? "صباح الخير" : hour < 18 ? "مساء الخير" : "مساء الخير",
    pt: hour < 12 ? "Bom dia" : hour < 18 ? "Boa tarde" : "Boa noite",
    ru: hour < 12 ? "Доброе утро" : hour < 18 ? "Добрый день" : "Добрый вечер",
    ja: hour < 12 ? "おはようございます" : hour < 18 ? "こんにちは" : "こんばんは",
  }

  if (greetingElement) {
    greetingElement.textContent = greetings[currentLang] || greetings.sq
  }
}

updateGreeting()

document.addEventListener("languageChanged", updateGreeting)

// Parallax effect for hero section
window.addEventListener("scroll", () => {
  const scrolled = window.pageYOffset
  const hero = document.querySelector(".hero-content")

  if (hero && scrolled < window.innerHeight) {
    hero.style.transform = `translateY(${scrolled * 0.3}px)`
    hero.style.opacity = 1 - scrolled / 800
  }
})

// Add loading animation to external links
document.querySelectorAll('a[target="_blank"]').forEach((link) => {
  link.addEventListener("click", function (e) {
    this.style.opacity = "0.6"
    setTimeout(() => {
      this.style.opacity = "1"
    }, 300)
  })
})

function createParticles() {
  const hero = document.querySelector(".hero")
  const particleCount = 30

  for (let i = 0; i < particleCount; i++) {
    const particle = document.createElement("div")
    particle.className = "particle"
    particle.style.cssText = `
      position: absolute;
      width: ${Math.random() * 4 + 1}px;
      height: ${Math.random() * 4 + 1}px;
      background: rgba(37, 99, 235, ${Math.random() * 0.3 + 0.1});
      border-radius: 50%;
      left: ${Math.random() * 100}%;
      top: ${Math.random() * 100}%;
      animation: float ${Math.random() * 10 + 10}s ease-in-out infinite;
      animation-delay: ${Math.random() * 5}s;
      pointer-events: none;
    `
    hero.appendChild(particle)
  }
}

createParticles()

document.querySelectorAll(".project-card").forEach((card) => {
  card.addEventListener("mousemove", (e) => {
    const rect = card.getBoundingClientRect()
    const x = e.clientX - rect.left
    const y = e.clientY - rect.top

    const centerX = rect.width / 2
    const centerY = rect.height / 2

    const deltaX = (x - centerX) / centerX
    const deltaY = (y - centerY) / centerY

    card.style.transform = `
      translateY(-12px) 
      rotateY(${deltaX * 5}deg) 
      rotateX(${-deltaY * 5}deg)
      scale(1.02)
    `
  })

  card.addEventListener("mouseleave", () => {
    card.style.transform = "translateY(0) rotateY(0) rotateX(0) scale(1)"
  })
})

const serviceCards = document.querySelectorAll(".service-card")

serviceCards.forEach((card) => {
  card.addEventListener("mousemove", (e) => {
    const rect = card.getBoundingClientRect()
    const x = e.clientX - rect.left
    const y = e.clientY - rect.top

    const centerX = rect.width / 2
    const centerY = rect.height / 2

    const deltaX = (x - centerX) / centerX
    const deltaY = (y - centerY) / centerY

    card.style.transform = `
      translateY(-12px) 
      rotateY(${deltaX * 8}deg) 
      rotateX(${-deltaY * 8}deg)
    `
  })

  card.addEventListener("mouseleave", () => {
    card.style.transform = "translateY(0) rotateY(0) rotateX(0)"
  })
})

function createRipple(event) {
  const button = event.currentTarget
  const circle = document.createElement("span")
  const diameter = Math.max(button.clientWidth, button.clientHeight)
  const radius = diameter / 2

  circle.style.width = circle.style.height = `${diameter}px`
  circle.style.left = `${event.clientX - button.offsetLeft - radius}px`
  circle.style.top = `${event.clientY - button.offsetTop - radius}px`
  circle.classList.add("ripple")

  const ripple = button.getElementsByClassName("ripple")[0]
  if (ripple) {
    ripple.remove()
  }

  button.appendChild(circle)
}

const buttons = document.querySelectorAll(".btn-primary, .btn-secondary, .contact-button")
buttons.forEach((button) => {
  button.addEventListener("click", createRipple)
})

const staggerElements = document.querySelectorAll(".service-card, .craft-card")
staggerElements.forEach((element, index) => {
  element.style.animationDelay = `${index * 0.1}s`
  observer.observe(element)
})

const cursorTrail = []
const trailLength = 10

document.addEventListener("mousemove", (e) => {
  if (window.innerWidth > 968) {
    cursorTrail.push({ x: e.clientX, y: e.clientY, time: Date.now() })

    if (cursorTrail.length > trailLength) {
      cursorTrail.shift()
    }

    const existingDots = document.querySelectorAll(".cursor-dot")
    existingDots.forEach((dot) => dot.remove())

    cursorTrail.forEach((point, index) => {
      const dot = document.createElement("div")
      const age = Date.now() - point.time
      const opacity = Math.max(0, 1 - age / 500)

      if (opacity > 0) {
        dot.className = "cursor-dot"
        dot.style.cssText = `
          position: fixed;
          width: ${8 - index * 0.5}px;
          height: ${8 - index * 0.5}px;
          background: rgba(37, 99, 235, ${opacity * 0.3});
          border-radius: 50%;
          left: ${point.x}px;
          top: ${point.y}px;
          pointer-events: none;
          z-index: 9999;
          transform: translate(-50%, -50%);
        `
        document.body.appendChild(dot)

        setTimeout(() => dot.remove(), 500)
      }
    })
  }
})

function typeWriter(element, text, speed = 50) {
  let i = 0
  element.textContent = ""

  function type() {
    if (i < text.length) {
      element.textContent += text.charAt(i)
      i++
      setTimeout(type, speed)
    }
  }

  type()
}


console.log("Portfolio loaded successfully")
console.log("[v0] Advanced animations and interactions loaded")

// Contact modal: open choice between Email and WhatsApp
;(function () {
  const contactButton = document.getElementById("contactButton")
  const modal = document.getElementById("contactModal")
  const overlay = document.getElementById("contactModalOverlay")
  const closeBtn = document.getElementById("contactModalClose")

  if (!contactButton || !modal) return

  function openModal() {
    modal.setAttribute("aria-hidden", "false")
    document.body.style.overflow = "hidden"
    // focus the first actionable element
    const first = modal.querySelector(".modal-action")
    if (first) first.focus()
  }

  function closeModal() {
    modal.setAttribute("aria-hidden", "true")
    document.body.style.overflow = ""
    contactButton.focus()
  }

  contactButton.addEventListener("click", (e) => {
    e.preventDefault()
    openModal()
  })

  overlay && overlay.addEventListener("click", closeModal)
  closeBtn && closeBtn.addEventListener("click", closeModal)

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && modal.getAttribute("aria-hidden") === "false") {
      closeModal()
    }
  })

  // Optional: when a modal action is clicked, allow default link behavior but close the modal
  modal.querySelectorAll(".modal-action").forEach((el) => {
    el.addEventListener("click", () => {
      closeModal()
    })
  })
  // Ensure mailto opens on systems where default handlers are unreliable.
  const modalEmail = document.getElementById("modalEmail")
  if (modalEmail) {
    modalEmail.addEventListener("click", (e) => {
      e.preventDefault()
      const href = modalEmail.getAttribute("href") || "mailto:kajtaziolti@gmail.com"
      console.log("[contact modal] email click ->", href)

      // Try several methods to open mail client, with fallbacks
      try {
        window.location.href = href
        setTimeout(closeModal, 150)
        return
      } catch (err) {
        console.warn("window.location failed for mailto", err)
      }

      try {
        const a = document.createElement("a")
        a.href = href
        a.style.display = "none"
        document.body.appendChild(a)
        a.click()
        a.remove()
        setTimeout(closeModal, 150)
        return
      } catch (err) {
        console.warn("programmatic anchor click failed for mailto", err)
      }

      try {
        window.open(href, "_self")
        setTimeout(closeModal, 150)
        return
      } catch (err) {
        console.warn("window.open failed for mailto", err)
      }

      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText("kajtaziolti@gmail.com")
        alert("Email address copied to clipboard: kajtaziolti@gmail.com\nPlease paste it into your mail app.")
      } else {
        alert("Please contact: kajtaziolti@gmail.com")
      }
    })
  }

  // WhatsApp fallback: open via window.open as redundancy
  const modalWA = document.getElementById("modalWhatsApp")
  if (modalWA) {
    modalWA.addEventListener("click", (e) => {
      e.preventDefault()
      const href = modalWA.getAttribute("href")
      console.log("[contact modal] whatsapp click ->", href)

      try {
        window.open(href, "_blank")
      } catch (err) {
        console.warn("window.open failed for whatsapp", err)
      }

      try {
        const a = document.createElement("a")
        a.href = href
        a.target = "_blank"
        a.rel = "noopener"
        a.style.display = "none"
        document.body.appendChild(a)
        a.click()
        a.remove()
      } catch (err) {
        console.warn("programmatic anchor click failed for whatsapp", err)
      }

      setTimeout(closeModal, 150)
    })
  }

})()
