$(document).ready(function() {
    $('#area_zipcode').mask('00000-000');

    $('#area_zipcode').on('blur', function() {
        var cep = $(this).val().replace(/\D/g, '');
        if (cep.length === 8) {
            $.ajax({
                url: `https://viacep.com.br/ws/${cep}/json/`,
                dataType: 'json',
                success: function(data) {
                    if (!data.erro) {
                        $('#area_street').val(data.logradouro);
                        $('#area_city').val(data.localidade);
                        $('#area_state').val(data.uf);
                    }
                }
            });
        }
    });

    $('#area_zipcode').on('input', function() {
        var value = $(this).val().replace(/\D/g, '');
        if (value.length > 5) {
            value = value.slice(0, 5) + '-' + value.slice(5);
        }
        $(this).val(value);
    });
});
