const value_field = document.getElementById('range_value');
const range_field = document.getElementById('select');
const showBtn = document.getElementById('show-btn');
const closeBtn = document.getElementById('close-btn');
const notesContainer = document.getElementById('notes-container');

value_field.innerHTML = range_field.value;

range_field.addEventListener('input', () => value_field.innerHTML = range_field.value);


showBtn.addEventListener('click', function() {
    notesContainer.classList.toggle('hidden');
    showBtn.classList.toggle('hidden');
});

closeBtn.addEventListener('click', function() {
    notesContainer.classList.toggle('hidden');
    showBtn.classList.toggle('hidden');
});
