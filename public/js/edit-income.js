document.addEventListener("DOMContentLoaded", function () {
    // Event listener untuk tombol edit pemasukan
    document.querySelectorAll(".btn-edit-income").forEach(button => {
        button.addEventListener("click", function () {
            let id = this.getAttribute("data-id");
            let name = this.getAttribute("data-name");
            let category = this.getAttribute("data-category");
            let amount = this.getAttribute("data-amount");
            let description = this.getAttribute("data-description");
            let transaction_date = this.getAttribute("data-transaction_date");

            // Isi nilai ke dalam form edit
            document.getElementById("income_id").value = id;
            document.getElementById("income_name").value = name;
            document.getElementById("income_category").value = category;
            document.getElementById("income_amount").value = amount;
            document.getElementById("income_description").value = description;
            document.getElementById("income_date").value = transaction_date;

            // Set form action untuk update
            let form = document.getElementById("editIncomeForm");
            form.setAttribute("action", `/income/${id}`);
        });
    });
});
