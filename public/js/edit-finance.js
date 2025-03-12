function editIncome(id) {
    $.get('/income/' + id + '/edit', function (data) {
        $('#editIncomeId').val(data.id);
        $('#editIncomeAmount').val(data.amount);
        $('#editIncomeForm').attr('action', '/income/' + id);
        $('#editIncomeModal').removeClass('hidden');
    });
}

function editExpense(id) {
    $.get('/expense/' + id + '/edit', function (data) {
        $('#editExpenseId').val(data.id);
        $('#editExpenseAmount').val(data.amount);
        $('#editExpenseForm').attr('action', '/expense/' + id);
        $('#editExpenseModal').removeClass('hidden');
    });
}
