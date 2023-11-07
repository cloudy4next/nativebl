<script>
    const Toast = swal.mixin({
        toast: true,
        position: 'top-right',
        iconColor: 'white',
        customClass: {
            popup: 'colored-toast'
        },
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true
    })
    Toast.fire({
        icon: '{{ $type }}',
        title: '{{ $message }}'
    })


</script>

{{--  Toast.fire({
        icon: 'success',
        title: 'Success'
    })
    Toast.fire({
        icon: 'error',
        title: 'Error'
    })
    Toast.fire({
        icon: 'warning',
        title: 'Warning'
    })
    Toast.fire({
        icon: 'info',
        title: 'Info'
    })
    Toast.fire({
        icon: 'question',
        title: 'Question'
    }) --}}