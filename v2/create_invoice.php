<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'includes/header.php';
?>
<style>
    .invoice-item-header, .invoice-item {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px solid #eee;
    }
    .invoice-item-header { font-weight: bold; }
    .invoice-item div { flex: 1; padding: 0 5px; }
    .invoice-item-header div, .invoice-item div { display: flex; align-items: center; }
    .invoice-item div:nth-child(1) { flex: 0 0 30px; } 
    .invoice-item div:nth-child(2) { flex: 3; }      
    .invoice-item div:nth-child(3) { flex: 2; }      
    .invoice-item div:nth-child(4) { flex: 2; }      
    .invoice-item div:nth-child(5) { flex: 2; }      
    .invoice-item div:nth-child(6) { flex: 0 0 50px; justify-content: flex-end; } 
    @media (max-width: 767px) {
        .invoice-item-header { display: none; }
        .invoice-item { flex-direction: column; padding: 1rem; border: 1px solid #ddd; border-radius: 5px; margin-bottom: 1rem; }
        .invoice-item div { padding: 5px 0; }
        .invoice-item div:before { content: attr(data-label); font-weight: bold; display: inline-block; width: 100px; }
    }
</style>

<div class="main-content container-fluid">
    <div class="page-title">
        <h3>Create Invoice</h3>
        <p class="text-subtitle text-muted">Fill out the form below to create a new invoice.</p>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="save_invoice.php" id="invoiceForm" class="form-horizontal">
                    <div class="row gy-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer_name">Customer Name</label>
                                <input class="form-control" name="customer_name" placeholder="Customer Name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer_phone">Customer Phone</label>
                                <input class="form-control" name="customer_phone" placeholder="Customer Phone">
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h5>Invoice Items</h5>
                    <div id="itemsContainer">
                        <div class="invoice-item-header">
                            <div>#</div>
                            <div>Description</div>
                            <div>Qty</div>
                            <div>Unit Cost</div>
                            <div>Amount</div>
                            <div></div>
                        </div>
                        <div id="itemsList">
                          
                        </div>
                    </div>

                    <button type="button" class="btn btn-secondary" onclick="addRow()">Add Item</button>
                    <hr>
                    <div class="row justify-content-end">
                        <div class="col-md-4 text-right">
                        <h4><strong>Total: </strong><span id="total">0.00</span></h4>
                        </div>
                    </div>
                    <input type="hidden" name="total_amount" id="totalInput">
                    <button class="btn btn-primary float-right mt-3">Save Invoice</button>
                </form>
            </div>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>
<script>
let rowCount = 0;
function addRow(){
  rowCount++;
  let list = document.getElementById("itemsList");
  let item = document.createElement("div");
  item.className = 'invoice-item';
  item.innerHTML = `<div data-label="#">${rowCount}</div>
    <div data-label="Description"><input name="description[]" class="form-control" required></div>
    <div data-label="Qty"><input type="number" name="quantity[]" class="form-control qty" value="1"></div>
    <div data-label="Unit Cost"><input type="number" step="0.01" name="unit_cost[]" class="form-control unit"></div>
    <div data-label="Amount" class="amount">0.00</div>
    <div><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.invoice-item').remove(); calcTotal()">X</button></div>`;
  list.appendChild(item);
}
document.addEventListener("input", function(e){
  if(e.target.classList.contains("qty") || e.target.classList.contains("unit")){
    let row = e.target.closest(".invoice-item");
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