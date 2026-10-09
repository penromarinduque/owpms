<script>
// Match Carbon::addMonthNoOverflow without parsing date-only strings as UTC.
function addOneMonth(dateField, resultElement) {
    const input = document.getElementById(dateField);
    const result = document.getElementById(resultElement);
    if (!input || !result) return;
    const parts = /^(\d{4})-(\d{2})-(\d{2})$/.exec(input.value);
    if (!parts) { result.textContent = 'Select a transport date'; return; }
    const year = Number(parts[1]), month = Number(parts[2]), day = Number(parts[3]);
    const lastDay = new Date(year, month + 1, 0).getDate();
    const validity = new Date(year, month, Math.min(day, lastDay));
    result.textContent = validity.toLocaleDateString('en-US', {year: 'numeric', month: 'short', day: 'numeric'});
}
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('transport_date');
    if (!input) return;
    const update = () => addOneMonth('transport_date', 'validity_result');
    input.addEventListener('change', update);
    update();
});
</script>
