// ============================================================
// 📚 BANCO DE PREGUNTAS
// Cada pregunta tiene: texto, respuestas válidas (array),
// y tipo (lógica, semántica, secuencia, etc.)
// ============================================================
var questions = [
  // 🔢 Matemática simple
  { q: "¿Cuánto es 5 + 3?",                     a: ["8", "ocho"] },
  { q: "¿Cuánto es 12 - 4?",                    a: ["8", "ocho"] },
  { q: "¿Cuánto es 3 × 4?",                     a: ["12", "doce"] },
  { q: "¿Cuántos lados tiene un triángulo?",     a: ["3", "tres"] },
  { q: "¿Cuántos días tiene una semana?",        a: ["7", "siete"] },
  { q: "¿Cuántos meses tiene un año?",           a: ["12", "doce"] },

  // 🧠 Lógica / Sentido común
  { q: "¿Qué es más pesado: 1kg de plumas o 1kg de hierro?",
                                                  a: ["igual", "lo mismo", "pesan igual", "ninguno"] },
  { q: "Si tienes 3 manzanas y comes 1, ¿cuántas te quedan?",
                                                  a: ["2", "dos"] },
  { q: "¿Cuántos huevos hay en una docena?",     a: ["12", "doce"] },

  // 🌍 Conocimiento general fácil
  { q: "¿De qué color es el cielo en un día despejado?",
                                                  a: ["azul"] },
  { q: "¿Cuál es el animal más grande del mundo?",
                                                  a: ["ballena", "ballena azul"] },
  { q: "¿En qué estación del año caen las hojas de los árboles?",
                                                  a: ["otoño"] },
  { q: "¿Cuántas patas tiene una araña?",        a: ["8", "ocho"] },
  { q: "¿Cuántas patas tiene un perro?",         a: ["4", "cuatro"] },

  // 🔤 Letras y palabras
  { q: "¿Cuántas letras tiene la palabra 'CASA'?",
                                                  a: ["4", "cuatro"] },
  { q: "¿Cuál es la primera letra del abecedario?",
                                                  a: ["a"] },
  { q: "¿Cómo se llama la capital de Francia?",  a: ["paris", "parís"] },
  { q: "¿Como se llama la playa mas conocida de Mar del Plata?",  a: ["bristol"] },
  { q: "¿Distancia entre CABA y Mardel?",  a: ["400"] },
];

// ============================================================
// 🎲 ESTADO DEL CAPTCHA
// ============================================================
currentQuestion = null;

// ============================================================
// 🔧 FUNCIONES
// ============================================================

/**
 * Selecciona una pregunta aleatoria distinta a la actual
 */
function getRandomQuestion() {
  let filtered = questions.filter(q => q !== currentQuestion);
  return filtered[Math.floor(Math.random() * filtered.length)];
}

/**
 * Normaliza el texto para comparar (minúsculas, sin tildes, sin espacios extra)
 */
function normalize(text) {
  return text
    .toLowerCase()
    .trim()
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g, ""); // elimina tildes
}

/**
 * Carga una nueva pregunta en el DOM
 */
function loadQuestion() {
  currentQuestion = getRandomQuestion();
  document.getElementById("captcha-question").textContent = `🤔 ${currentQuestion.q}`;
  document.getElementById("captcha-answer").value = "";
  clearFeedback();
}

/**
 * Valida la respuesta del usuario
 * @returns {boolean}
 */
function validateCaptcha() {
  const userAnswer = normalize(document.getElementById("captcha-answer").value);
  const validAnswers = currentQuestion.a.map(normalize);

  return validAnswers.includes(userAnswer);
}

/**
 * Muestra feedback visual al usuario
 */
function showFeedback(isValid) {
  const feedback = document.getElementById("captcha-feedback");
  if (isValid) {
    feedback.textContent = "✅ ¡Correcto!";
    feedback.className = "feedback-ok";
  } else {
    feedback.textContent = "❌ Respuesta incorrecta. Inténtalo de nuevo.";
    feedback.className = "feedback-err";
  }
}

function clearFeedback() {
  const feedback = document.getElementById("captcha-feedback");
  feedback.textContent = "";
  feedback.className = "";
}

// ============================================================
// 🚀 EVENTOS
// ============================================================

// Cargar pregunta inicial al arrancar
document.addEventListener("DOMContentLoaded", loadQuestion);

// Botón de refrescar pregunta
document.getElementById("refresh-btn").addEventListener("click", loadQuestion);

// Validar en tiempo real mientras el usuario escribe
document.getElementById("captcha-answer").addEventListener("input", () => {
  if (validateCaptcha()) {
    showFeedback(true);
  } else {
    clearFeedback();
  }
});

// Validar al enviar el formulario
document.getElementById("myForm").addEventListener("submit", (e) => {
  e.preventDefault();

  if (validateCaptcha()) {
    showFeedback(true);
    alert("✅ Formulario enviado correctamente. ¡Gracias!");
    loadQuestion(); // Resetea el captcha
  } else {
    showFeedback(false);
    loadQuestion(); // Nueva pregunta tras fallo
  }
});// JavaScript Document