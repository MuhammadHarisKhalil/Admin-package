@error('alert-danger')
    <div class="w-100 p-1 label-danger">
        <span class="tag label fs-5">{{$message }}</span>
    </div>
@enderror
@error('alert-info')
    <div class="w-100 p-1 label-info">
        <span class="tag label fs-5">{{$message }}</span>
    </div>
@enderror
@error('alert-warning')
    <div class="w-100 p-1 label-warning">
        <span class="tag label fs-5">{{$message }}</span>
    </div>
@enderror
@error('alert-success')
    <div class="w-100 p-1 label-success">
        <span class="tag label fs-5">{{$message }}</span>
    </div>
@enderror