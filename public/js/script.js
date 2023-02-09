$("#dateWiseInvoiceEntry").click(function (event) {
    event.preventDefault();

    let projectId = $("#projectId").val();
    let firstDate = $("#firstDate").val();
    let lastDate = $("#lastDate").val();
    let invoiceTitle = $("#invoiceTitle").val();
    let _token = $("input[name=_token]").val();

    if ((firstDate.length != '') || (lastDate.length != '')) {
        $("#message").hide();
    }

    if ((firstDate.length == '') || (lastDate.length == '')) {
        $("#message").text('enter dates first');
    }
    else {
        $.ajax({
            url: "/ajax-call",
            type: "POST",
            dataType: 'json',
            data: { firstDate: firstDate, invoiceTitle: invoiceTitle, lastDate: lastDate, _token: _token, projectId: projectId },
            success: function (response) {
                if (response) {

                    if (confirm('Invoice Title : ' + invoiceTitle + '\nProject Name : ' + response.projectName + '\nInvoice Rate : ' + response.invoiceRate + '\nTotal Hours : ' + response.totalHours + '\n\nFirst Date : ' + response.startDate + '\nLast Date : ' + response.lastDate + '\nDo your want to create Invoice ?')) {
                        let projectName = response.projectName;
                        let invoiceTitle = response.invoiceTitle;
                        let totalHours = response.totalHours;
                        let invoiceRate = response.invoiceRate;
                        let startDate = response.startDate;
                        let lastDate = response.lastDate;

                        $.ajax({
                            url: "/ajax-entry",
                            type: "POST",
                            data: {
                                projectName: projectName, invoiceTitle: invoiceTitle, _token: _token, totalHours: totalHours, invoiceRate: invoiceRate,
                                startDate: startDate, lastDate: lastDate, projectId: projectId
                            },
                            // dataType: 'json',
                            success: function (response) {
                                if (response) {
                                    alert(response);
                                    $("#message").html(response);
                                }
                            },
                            error: function (error) {
                                console.log(error);
                            }
                        });
                    }
                    else {
                        $('#message').html('Refuse to create invoice');
                    }
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    }
});