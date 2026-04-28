import { useState } from 'react'
import ExcuseForm from './components/ExcuseForm'
import ExcuseResult from './components/ExcuseResult'
import './App.css'

function App() {
  const [result, setResult] = useState(null)
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState(null)

  async function handleSubmit(formData) {
    setLoading(true)
    setError(null)
    setResult(null)

    try {
      const res = await fetch('/api/generate', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ ...formData, user_type: 'free' }),
      })

      const data = await res.json()

      if (!res.ok) {
        if (data.error === 'limit_reached') {
          setError('Llegaste al límite de 3 solicitudes diarias del plan gratis. Volvé mañana 👋')
        } else {
          setError(data.error || 'Algo salió mal. Intentá de nuevo.')
        }
        return
      }

      setResult(data)
    } catch {
      setError('No se pudo conectar al servidor. ¿Está corriendo el backend?')
    } finally {
      setLoading(false)
    }
  }

  function handleReset() {
    setResult(null)
    setError(null)
  }

  return (
    <div className="page">
      <header className="page-header">
        <div className="header-badge">IA · Gratis · 3 usos por día</div>
        <h1>Generador de Excusas</h1>
        <p className="header-subtitle">
          Contame tu situación y te ayudo a safar con una excusa creíble y a medida.
        </p>
      </header>

      <main className="page-main">
        <div className="card">
          {!result ? (
            <ExcuseForm onSubmit={handleSubmit} loading={loading} error={error} />
          ) : (
            <ExcuseResult result={result} onReset={handleReset} />
          )}
        </div>
      </main>

      <footer className="page-footer">
        Usá las excusas con responsabilidad 😅
      </footer>
    </div>
  )
}

export default App
