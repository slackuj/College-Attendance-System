const urlParams = new URLSearchParams(window.location.search);

//check if parameter exists
if (urlParams.has('deleted') && urlParams.has('teacher')) {
    const deleted = urlParams.get('deleted');
    const teacher = urlParams.get('teacher');
    if (deleted === 'true'){
        toastr.remove();
        toastr.options = {};
        toastr.options.positionClass = 'toast-top-right';
        const title = `Deleted Teacher`;
        const message = `${teacher} has been deleted successfully`;
        toastr.success(message, title);
    }
}