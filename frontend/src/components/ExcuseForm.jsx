import { useState } from 'react'

const CONTEXTS = [
  { value: 'work', label: '💼 Trabajo' },
  { value: 'relationship', label: '💑 Pareja' },
  { value: 'friends', label: '🍻 Amigos' },
]

const OBJECTIVES = [
  { value: 'cancel', label: 'Cancelar planes' },
  { value: 'delay', label: 'Postergar algo' },
  { value: 'avoid', label: 'Evitar una situación' },
]

const PERSON_TYPES = [
  { value: '', label: 'No sé / prefiero no decir' },
  { value: 'comprensiva y flexible', label: 'Comprensiva y flexible' },
  { value: 'estricta y exigente', label: 'Estricta y exigente' },
  { value: 'desconfiada y difícil de convencer', label: 'Desconfiada / difícil de convencer' },
  { value: 'muy cercana, casi como familia', label: 'Muy cercana (casi familia)' },
  { value: 'formal y de trato profesional', label: 'Formal / trato profesional' },
]

export default function ExcuseForm({ onSubmit, loading, error }) {
  const [form, setForm] = useState({
    context: 'work',
    objective: 'cancel',
    person_type: '',
    description: '',
  })

  function handleChange(e) {
    setForm(prev => ({ ...prev, [e.target.name]: e.target.value }))
  }

  function handleSubmit(e) {
    e.preventDefault()
    onSubmit(form)
  }

  return (
    <form className="excuse-form" onSubmit={handleSubmit}>
      <div className="form-row">
        <div className="form-group">
          <label htmlFor="context">¿En qué contexto?</label>
          <select id="context" name="context" value={form.context} onChange={handleChange}>
            {CONTEXTS.map(c => (
              <option key={c.value} value={c.value}>{c.label}</option>
            ))}
          </select>
        </div>

        <div className="form-group">
          <label htmlFor="objective">¿Qué necesitás?</label>
          <select id="objective" name="objective" value={form.objective} onChange={handleChange}>
            {OBJECTIVES.map(o => (
              <option key={o.value} value={o.value}>{o.label}</option>
            ))}
          </select>
        </div>
      </div>

      <div className="form-group">
        <label htmlFor="person_type">¿Cómo es la otra persona?</label>
        <select id="person_type" name="person_type" value={form.person_type} onChange={handleChange}>
          {PERSON_TYPES.map(p => (
            <option key={p.value} value={p.value}>{p.label}</option>
          ))}
        </select>
      </div>

      <div className="form-group">
        <label htmlFor="description">
          Contame un poco más <span className="optional">(opcional pero ayuda mucho)</span>
        </label>
        <textarea
          id="description"
          name="description"
          value={form.description}
          onChange={handleChange}
          placeholder="Ej: Es una reunión de lunes a las 8am con mi jefe directo, hace 2 semanas que la vengo postergando..."
          rows={3}
        />
      </div>

      {error && <p className="form-error">{error}</p>}

      <button type="submit" className="btn-primary" disabled={loading}>
        {loading
          ? <><span className="spinner" /> Generando tu excusa...</>
          : '✨ Generá mi excusa'}
      </button>
    </form>
  )
}
