# 📄 SPEC FUNCIONAL — Generador de Excusas (MVP)

## 1. 🎯 Objetivo

Permitir a usuarios generar excusas personalizadas, creíbles y listas para usar en situaciones sociales incómodas mediante IA, bajo un modelo freemium.

---

## 2. 👤 Tipos de usuario

### Usuario anónimo
- Hasta 3 generaciones por día
- Sin historial
- Sin personalización avanzada

### Usuario registrado (free)
- Hasta 5 generaciones por día
- Historial básico
- Puede guardar favoritas

### Usuario premium
- Generaciones ilimitadas
- Personalización completa
- Estilos avanzados
- Historial completo
- Sin publicidad

---

## 3. 🧩 Casos de uso

### CU01 — Generar excusa
**Flujo:**
1. Usuario completa:
   - contexto (trabajo / pareja / amigos / otro)
   - objetivo (cancelar / llegar tarde / evitar)
   - descripción opcional
2. Envía formulario
3. Sistema procesa con IA
4. Devuelve:
   - excusa
   - versión corta
   - credibilidad (%)
   - riesgo (bajo/medio/alto)
   - recomendación

---

### CU02 — Ver resultado
- Copiar excusa
- Regenerar
- Compartir

---

### CU03 — Guardar excusa
- Solo usuarios logueados

---

### CU04 — Autenticación
- Registro con email/password
- Login

---

### CU05 — Upgrade a premium
- Trigger al alcanzar límite
- Visualización de beneficios

---

## 4. 🧠 Lógica de negocio

### Generación IA
**Input:**
- contexto
- objetivo
- descripción
- tipo de usuario

**Output:**
- excusa
- versión corta
- credibilidad
- riesgo
- recomendación

---

### Sistema freemium
- Control por IP o user_id
- Límite diario
- Bloqueo + CTA upgrade

---

### Sistema de riesgo (MVP)
- trabajo + cancelación urgente → alto
- pareja → medio/alto
- amigos → bajo

---

## 5. 🖥️ Pantallas

### Landing
- Propuesta de valor
- CTA

### Generador
- Formulario principal

### Resultado
- Excusa + métricas
- Acciones (copiar, regenerar, compartir)

### Auth
- Login / Register

### Paywall
- Beneficios + CTA

---

## 6. 📊 Métricas

- conversion free → paid
- excusas generadas
- shares
- retención

---

## 7. 🔌 Integraciones

- API IA
- sistema de pagos
- almacenamiento

---

## 8. 🗂️ Modelo de datos

### User
- id
- email
- password_hash
- plan
- created_at

### Excuse
- id
- user_id
- contexto
- objetivo
- descripcion
- resultado_json
- created_at

### Usage
- id
- user_id/ip
- date
- count

---

## 9. ⚙️ No funcionales

- < 3s respuesta
- mobile-first
- UI simple

---

## 10. 🚀 Alcance MVP

✔ Generación  
✔ Límite freemium  
✔ UI básica  
✔ Copy/share  
✔ Auth simple  
✔ Paywall básico  

---

## 11. ❌ Fuera de alcance

- app mobile
- features sociales
- IA propia
- multi idioma

---

## 12. 🧪 Criterios de aceptación

- Generación < 10s total
- Output completo siempre
- Límite funciona
- Copy sin fricción
- Upgrade visible