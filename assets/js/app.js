// Menú móvil
const b = document.querySelector('.burger'), m = document.getElementById('menu');
if (b) b.addEventListener('click', () => { const o = m.classList.toggle('open'); b.setAttribute('aria-expanded', o); });
// Confirmar acciones destructivas
document.addEventListener('click', e => {
  const t = e.target.closest('[data-confirm]');
  if (t && !confirm(t.dataset.confirm)) e.preventDefault();
});
// Filtros de portada: al elegir uno, ir a la lista filtrada
document.querySelectorAll('.filtros select').forEach(s => s.addEventListener('change', () => s.form.submit()));
// Ocultar avisos automáticamente
setTimeout(() => document.querySelectorAll('.alert.ok').forEach(a => a.style.display = 'none'), 6000);
