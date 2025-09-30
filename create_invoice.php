<?php include 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
} include 'header.php'; ?>
<h2>Create Invoice</h2>
<form method="POST" action="save_invoice.php" id="invoiceForm">
  <div class="mb-2">
    <input class="form-control" name="customer_name" placeholder="Customer Name" required>
  </div>
  <div class="mb-2">
    <input class="form-control" name="customer_phone" placeholder="Customer Phone">
  </div>
  <table class="table" id="itemsTable">
    <thead><tr><th>#</th><th>Description</th><th>Qty</th><th>Unit Cost</th><th>Amount</th><th></th></tr></thead>
    <tbody></tbody>
  </table>
  <button type="button" class="btn btn-secondary" onclick="addRow()">Add Item</button>
  <hr>
  <div><strong>Total: </strong><span id="total">0.00</span></div>
  <input type="hidden" name="total_amount" id="totalInput">
  <button class="btn btn-primary">Save Invoice</button>
</form>
<script>
let rowCount = 0;
function addRow(){
  rowCount++;
  let table = document.getElementById("itemsTable").querySelector("tbody");
  let row = document.createElement("tr");
  row.innerHTML = `<td>${rowCount}</td>
    <td><input name="description[]" class="form-control" required></td>
    <td><input type="number" name="quantity[]" class="form-control qty" value="1"></td>
    <td><input type="number" step="0.01" name="unit_cost[]" class="form-control unit"></td>
    <td class="amount">0.00</td>
    <td><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove(); calcTotal()">X</button></td>`;
  table.appendChild(row);
}
document.addEventListener("input", function(e){
  if(e.target.classList.contains("qty") || e.target.classList.contains("unit")){
    let row = e.target.closest("tr");
    let qty = parseFloat(row.querySelector(".qty").value)||0;
    let unit = parseFloat(row.querySelector(".unit").value)||0;
    let amount = qty * unit;
    row.querySelector(".amount").innerText = amount.toFixed(2);
    calcTotal();
  }
});
function calcTotal(){
  let total = 0;
  document.querySelectorAll(".amount").forEach(td => total += parseFloat(td.innerText));
  document.getElementById("total").innerText = total.toFixed(2);
  document.getElementById("totalInput").value = total.toFixed(2);
}
</script>
<?php include 'footer.php'; ?>
