<div>
    <ul>
        <li class="list-inline-item"><i class="fa fa-star{{ $course->custom_rating >= 1 ? ' yellow' : '' }}"></i></li>
        <li class="list-inline-item"><i class="fa fa-star{{ $course->custom_rating >= 2 ? ' yellow' : '' }}"></i></li>
        <li class="list-inline-item"><i class="fa fa-star{{ $course->custom_rating >= 3 ? ' yellow' : '' }}"></i></li>
        <li class="list-inline-item"><i class="fa fa-star{{ $course->custom_rating >= 4 ? ' yellow' : '' }}"></i></li>
        <li class="list-inline-item"><i class="fa fa-star{{ $course->custom_rating >= 5 ? ' yellow' : '' }}"></i></li>
    </ul>
</div>
