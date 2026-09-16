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
        <div class="card-body projects-body">
            @if ($author->project->count() > 0 )
            <div class="table-responsive">
                <table class="table w-100 projects-table">
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
                                <div class="font-semibold text-slate-800">{{ $project->project_title }}</div>
                                @if(!empty($project->investigation_role))
                                    <div class="text-xs text-slate-500 mt-0.5">{{ $project->investigation_role }}</div>
                                @endif
                                @if($project->outputs && $project->outputs->count() > 0)
                                    <div class="mt-2 flex flex-wrap gap-1.5">
                                        <span class="text-[11px] text-slate-400 font-medium block w-full">{{ __('Publicações Associadas:') }}</span>
                                        @foreach($project->outputs as $linkedOut)
                                            <a href="{{ route('outputs.show', $linkedOut->id) }}" class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] bg-emerald-50 text-emerald-800 border border-emerald-200 hover:bg-emerald-100 transition-colors">
                                                <i class="fas fa-book-open text-[10px] text-emerald-600"></i>
                                                <span class="truncate max-w-xs">{{ $linkedOut->title }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
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