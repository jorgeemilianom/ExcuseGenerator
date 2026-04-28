# 🧱 SPEC TÉCNICO — Generador de Excusas (MVP)

## 1. 🧰 Stack Tecnológico

### Frontend
- Vite
- React
- Fetch API
- Estado: useState (MVP) o Zustand (opcional)

### Backend
- PHP (sin framework)
- Arquitectura modular simple
- API REST

### Infraestructura
- Monorepo
- Deploy desacoplado o conjunto

### Integraciones
- OpenAI API (generación de contenido)

---

## 2. 📁 Estructura del Proyecto


/excuse-generator/
│
├── /frontend/
│ ├── /src/
│ │ ├── /components/
│ │ ├── /pages/
│ │ ├── /services/
│ │ ├── /hooks/
│ │ ├── /store/
│ │ └── main.jsx
│ ├── index.html
│ └── vite.config.js
│
├── /backend/
│ ├── /public/
│ │ └── index.php
│ ├── /src/
│ │ ├── Controllers/
│ │ ├── Services/
│ │ ├── Middleware/
│ │ ├── Utils/
│ │ └── Router.php
│ ├── /config/
│ │ └── config.php
│ ├── /storage/
│ │ └── usage.json
│ └── .env
│
└── README.md


---

## 3. 🧠 Arquitectura General

### Frontend
- Aplicación SPA
- Comunicación vía REST (`/api/*`)
- Manejo de estado local
- Flujo simple: Form → API → Resultado

### Backend
- Router centralizado
- Controllers: manejo de requests
- Services: lógica de negocio
- Utils: helpers reutilizables

---

## 4. 🔌 API Endpoints

### POST `/api/generate`

#### Request
```json
{
  "context": "trabajo",
  "objective": "cancelar",
  "description": "reunion importante",
  "user_type": "free"
}
Response
{
  "excuse": "Texto largo generado",
  "short_version": "Texto corto",
  "credibility": 85,
  "risk": "medio",
  "recommendation": "Sugerencia de seguimiento"
}
POST /api/auth/register
Request
{
  "email": "user@mail.com",
  "password": "123456"
}
POST /api/auth/login
Response
{
  "token": "jwt_token",
  "user": {
    "id": 1,
    "plan": "free"
  }
}
GET /api/usage
Response
{
  "count": 2,
  "limit": 3
}
5. 🧩 Backend — Componentes
5.1 Router

Responsable de mapear rutas a controllers.

class Router {
    public function resolve($method, $uri) {
        if ($uri === '/api/generate' && $method === 'POST') {
            (new GenerateController())->handle();
        }
    }
}
5.2 Controller

Recibe request y delega lógica.

class GenerateController {
    public function handle() {
        $input = json_decode(file_get_contents("php://input"), true);

        $service = new GenerateExcuseService();
        $result = $service->generate($input);

        echo json_encode($result);
    }
}
5.3 Service (Lógica principal)
class GenerateExcuseService {

    public function generate($data) {
        $prompt = $this->buildPrompt($data);
        $response = $this->callAI($prompt);

        return $this->formatResponse($response, $data);
    }
}
5.4 Integración OpenAI
private function callAI($prompt) {
    $apiKey = getenv('OPENAI_API_KEY');

    $payload = [
        "model" => "gpt-4.1-mini",
        "messages" => [
            ["role" => "user", "content" => $prompt]
        ]
    ];

    // Implementación con cURL
}
5.5 Sistema de uso (freemium)

Archivo plano:

/backend/storage/usage.json

Formato:

{
  "ip_127_0_0_1": {
    "date": "2026-04-23",
    "count": 2
  }
}

Lógica:

Identificación por IP o user_id
Reset diario
Validación antes de generar
5.6 Middleware (opcional MVP)
CORS
Validación básica
Rate limit simple
6. ⚛️ Frontend
6.1 API Service
export const generateExcuse = async (data) => {
  const res = await fetch('/api/generate', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(data)
  });

  return res.json();
};
6.2 Estado
const [result, setResult] = useState(null);
6.3 Flujo
Usuario completa formulario
Se ejecuta POST /api/generate
Se guarda resultado en estado
Se renderiza resultado
6.4 UX mínima requerida
Loading state
Botón copiar
Botón regenerar
Feedback visual
7. 🔐 Autenticación
JWT Payload
{
  "user_id": 1,
  "plan": "free"
}
Almacenamiento
localStorage
8. 💳 Paywall
Lógica frontend + backend
Restricción por user_type
Trigger automático al alcanzar límite
9. ⚙️ Configuración
Archivo .env
OPENAI_API_KEY=your_api_key
APP_ENV=local
10. 🚀 Deploy
Opción 1 (desacoplado)
Frontend: Vercel
Backend: VPS (Apache/Nginx)
Opción 2 (unificado)
Build frontend con Vite
Servir desde /backend/public
11. 🔄 Escalabilidad futura
Migración a Laravel o Symfony
Base de datos (PostgreSQL)
Redis para caching
Rate limiting robusto
Autenticación OAuth
Multi-tenant
12. ⚠️ Decisiones técnicas clave
PHP plano para velocidad de desarrollo
API REST para desacople
Monorepo para simplicidad inicial
Arquitectura modular para migración futura
13. 🧪 Consideraciones de performance
Respuesta API < 3s
Manejo de errores IA
Timeout en requests externos
Logging básico en /storage/logs
14. 🛡️ Seguridad (básico MVP)
Sanitización de inputs
Validación mínima
Protección contra abuso (rate limit simple)

---