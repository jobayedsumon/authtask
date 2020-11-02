@extends('master')

@section('content')

    <div class="container">

        <div class="col-md-10 offset-md-2">
            <h1>Users</h1>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Created</th>
                </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->created_at }}</td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
            <div class="paginateNav">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <li class="page-item">
                            <a class="page-link" href="/users?page_no={{ $page_no - 1 }}&limit={{ $limit }}" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Previous</span>
                            </a>
                        </li>
                        @for($i = 1; $i <= $total_page; $i++)
                        <li class="page-item"><a class="page-link {{ $i == $page_no ? 'active' : '' }}"
                                                 href="/users?page_no={{ $i }}&limit={{ $limit }}">{{ $i }}</a></li>
                        @endfor
                        <li class="page-item">
                            <a class="page-link" href="/users?page_no={{ $page_no + 1 }}&limit={{ $limit }}" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Next</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>

        </div>





{{--        @if ($paginator->lastPage() > 1)--}}
{{--            <ul class="pagination">--}}
{{--                <li class="{{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}">--}}
{{--                    <a href="{{ $paginator->url(1) }}">Previous</a>--}}
{{--                </li>--}}
{{--                @for ($i = 1; $i <= $paginator->lastPage(); $i++)--}}
{{--                    <li class="{{ ($paginator->currentPage() == $i) ? ' active' : '' }}">--}}
{{--                        <a href="{{ $paginator->url($i) }}">{{ $i }}</a>--}}
{{--                    </li>--}}
{{--                @endfor--}}
{{--                <li class="{{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}">--}}
{{--                    <a href="{{ $paginator->url($paginator->currentPage()+1) }}" >Next</a>--}}
{{--                </li>--}}
{{--            </ul>--}}
{{--        @endif--}}

    </div>



@endsection
