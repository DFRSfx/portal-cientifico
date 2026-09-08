@extends('authors.show')

@section('author-information')
<div class="accordion-item">
    <div class="accordion-header" id="headingProjects">
        <h5 class="mb-0">
            <button class="accordion-button" data-mdb-toggle="collapse" data-mdb-target="#collapseProjects" aria-expanded="true" aria-controls="collapseProjects">
                <b>{{ __("Projetos") }}</b>
            </button>
        </h5>
    </div>
    <div id="collapseProjects" class="collapse show" aria-labelledby="headingProjects" data-parent="#accordion">
        <div class="card-body">
            @if ($author->project->count() > 0 )
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="row">{{ __('Ano') }}</th>
                            <th scope="row">{{ __('Projeto') }}</th>
                            <th scope="row">{{ __('Estado') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($author->project as $project)
                        <tr>
                            <td>
                                <x-date-component :model=$project initialDateAttribute="start_date" finalDateAttribute="end_date"/>
                            </td>
                            <td>
                                {{ $project->project_title }}
                                <br>
                                {{ $project->investigation_role }}
                            </td>
                            <td>
                                <span class="badge rounded-pill  d-inline @if ($project->status == 'Ongoing') badge-primary @elseif($project->status == 'Cancelled') badge-danger @else badge-success @endif">{{ $project->status }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td>{{ __('Sem Projetos') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @else
            <span>{{ __('Sem Projetos') }}</span>
            @endif
        </div>
    </div>
</div>
@endsection