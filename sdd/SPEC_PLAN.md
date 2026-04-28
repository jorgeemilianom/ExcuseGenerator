# 📦 SPEC PLAN — Generador de Excusas (MVP)

## 1. 🎯 Objetivo del plan

Definir el roadmap técnico y operativo para construir el MVP de forma rápida, validando la idea en el menor tiempo posible con el menor esfuerzo.

---

## 2. 🧭 Estrategia general

- Prioridad: velocidad sobre perfección
- Enfoque: funcionalidad core primero
- Arquitectura: simple pero escalable
- Iteración: validar → mejorar → escalar

---

## 3. 🪜 Fases de desarrollo

---

## 🔹 FASE 1 — Setup inicial

### Objetivo
Tener el entorno listo para empezar a desarrollar.

### Tareas

- Crear repo monorepo
- Inicializar frontend con Vite + React
- Crear estructura backend PHP
- Configurar `.env`
- Configurar CORS en backend

### Resultado esperado
Proyecto corre localmente (frontend + backend)

---

## 🔹 FASE 2 — Core backend (API generación)

### Objetivo
Tener endpoint funcional que genere excusas

### Tareas

- Crear Router.php
- Crear GenerateController
- Crear GenerateExcuseService
- Implementar integración con OpenAI
- Definir prompt base
- Devolver estructura de respuesta

### Validación
- Testear endpoint con Postman
- Confirmar respuesta correcta

---

## 🔹 FASE 3 — Frontend básico

### Objetivo
Poder generar excusas desde UI

### Tareas

- Crear página Generator
- Crear formulario (contexto, objetivo, descripción)
- Implementar llamada a `/api/generate`
- Manejar estado (loading, result)
- Mostrar resultado

### Validación
- Flujo completo funcionando en UI

---

## 🔹 FASE 4 — UX mínima viable

### Objetivo
Mejorar usabilidad básica

### Tareas

- Agregar loading spinner
- Botón copiar al portapapeles
- Botón regenerar
- Feedback visual

---

## 🔹 FASE 5 — Sistema freemium

### Objetivo
Limitar uso y preparar monetización

### Tareas

- Crear `usage.json`
- Implementar contador por IP
- Validar límite antes de generar
- Mostrar mensaje de límite alcanzado
- Crear UI básica de paywall

---

## 🔹 FASE 6 — Autenticación básica

### Objetivo
Preparar base para usuarios registrados

### Tareas

- Endpoint register
- Endpoint login
- Generar JWT simple
- Guardar token en frontend
- Enviar token en requests

---

## 🔹 FASE 7 — Guardado de excusas (opcional MVP+)

### Objetivo
Mejorar retención

### Tareas

- Endpoint guardar excusa
- Asociar con user_id
- UI de favoritos

---

## 🔹 FASE 8 — Deploy

### Objetivo
Publicar MVP online

### Opciones

#### Opción A (rápida)
- Frontend: Vercel
- Backend: VPS

#### Opción B (simple)
- Build frontend
- Servir desde backend

---

## 4. 🧪 Testing mínimo

- Test manual del flujo completo
- Test de límite freemium
- Test de errores (IA caída, inputs vacíos)

---

## 5. 📊 Métricas a implementar

- cantidad de generaciones
- usuarios únicos
- tasa de límite alcanzado
- conversion a premium (fase futura)

---

## 6. ⚠️ Riesgos y mitigación

### Riesgo: respuestas malas de IA
- Mitigación: mejorar prompt

### Riesgo: abuso del sistema
- Mitigación: límite por IP

### Riesgo: baja retención
- Mitigación: mejorar UX + personalización

---

## 7. 🚀 Tiempo estimado

| Fase | Tiempo |
|------|------|
| Setup | 2-3 hs |
| Backend core | 4-6 hs |
| Frontend básico | 4-6 hs |
| UX + freemium | 4 hs |
| Auth | 3-4 hs |
| Deploy | 2 hs |

**Total estimado: 1–2 días**

---

## 8. 🎯 Definición de MVP listo

El MVP está listo cuando:

- Se pueden generar excusas desde la web
- Existe límite freemium funcional
- UI usable (aunque simple)
- Está deployado públicamente

---

## 9. 🔄 Próximos pasos post-MVP

- Mejorar prompts (clave)
- Agregar estilos de excusas
- Implementar pagos
- SEO pages
- Viral features (share)

---

## 10. 🧠 Principio clave

> “Mejor hecho hoy que perfecto nunca”

El objetivo no es escalar, es validar.