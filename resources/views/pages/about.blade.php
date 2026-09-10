<x-app-layout>
    <x-slot:title>
        {{ __('Sobre Nós') }}
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap');

        .about-page {
            font-family: 'Poppins', sans-serif;
            min-height: calc(100vh - 120px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 56px 20px 72px 20px;
            color: #0f172a;
        }

        .about-header {
            text-align: center;
            max-width: 860px;
            margin-bottom: 48px;
        }

        .about-title {
            font-size: clamp(2rem, 3.5vw, 2.75rem);
            font-weight: 700;
            color: #2e7a3f;
            letter-spacing: -0.025em;
            line-height: 1.2;
            margin: 0 0 16px 0;
        }

        .about-lead {
            font-size: clamp(0.9375rem, 1.3vw, 1.0625rem);
            color: #64748b;
            line-height: 1.7;
            margin: 0 auto;
            max-width: 780px;
        }

        /* Side-by-side snippet structure */
        .about-showcase {
            width: 100%;
            max-width: 1100px;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            gap: 56px;
            margin-bottom: 56px;
        }

        .about-image-col {
            flex: 1;
            max-width: 440px;
            display: flex;
            justify-content: center;
        }

        .about-image-card {
            width: 100%;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 24px 60px -12px rgba(15, 23, 42, 0.14), 0 8px 24px -4px rgba(15, 23, 42, 0.06);
            overflow: hidden;
            background: #ffffff;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .about-image-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 32px 70px -12px rgba(15, 23, 42, 0.18);
        }

        .about-image-card img {
            width: 100%;
            height: auto;
            display: block;
            object-fit: cover;
        }

        .about-content-col {
            flex: 1.25;
            max-width: 580px;
            text-align: left;
        }

        .about-paragraphs {
            display: flex;
            flex-direction: column;
            gap: 22px;
            margin-bottom: 32px;
        }

        .about-paragraph-item {
            display: flex;
            align-items: flex-start;
            gap: 16px;
        }

        .about-item-icon {
            width: 40px;
            height: 40px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            border-radius: 10px;
            color: #2e7a3f;
            box-shadow: 0 2px 6px rgba(46, 122, 63, 0.08);
            margin-top: 2px;
        }

        .about-paragraph-item p {
            font-size: 0.9375rem;
            color: #475569;
            line-height: 1.68;
            margin: 0;
        }

        /* POCH Logos Card */
        .about-poch-card {
            width: 100%;
            max-width: 1100px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 32px 36px;
            box-shadow: 0 10px 30px -6px rgba(15, 23, 42, 0.05);
            text-align: center;
            margin-bottom: 40px;
        }

        .about-poch-logo-wrap {
            max-width: 800px;
            margin: 0 auto;
        }

        .about-poch-logo-wrap img {
            width: 100%;
            height: auto;
            display: block;
        }

        /* CTA Button */
        .btn-about-cta {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: linear-gradient(115deg, #2e7a3f, #1f572d);
            color: #ffffff !important;
            font-weight: 700;
            font-size: 0.875rem;
            letter-spacing: 0.3px;
            padding: 12px 32px;
            border-radius: 12px;
            border: none;
            text-decoration: none;
            box-shadow: 0 8px 20px rgba(31, 87, 45, 0.28);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .btn-about-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(31, 87, 45, 0.35);
            opacity: 0.96;
            color: #ffffff !important;
        }

        @media (max-width: 900px) {
            .about-showcase {
                flex-direction: column;
                gap: 36px;
            }
            .about-content-col {
                text-align: left;
            }
            .about-page {
                padding: 40px 16px;
            }
            .about-poch-card {
                padding: 24px 18px;
            }
        }
    </style>

    <div class="about-page">
        <!-- Header with exact original title and paragraph 1 -->
        <div class="about-header">
            <h1 class="about-title">{{ __('Portal científico') }}</h1>
            <p class="about-lead">
                {{ __('Este projeto foi financiado pelo Programa Operacional de Capital Humano (POCH), com o objetivo de desenvolver e implementar soluções inovadoras para melhorar a eficiência e a qualidade dos serviços prestados pelas empresas de ensino') }}
            </p>
        </div>

        <!-- Showcase: image on left, exact paragraphs 2, 3, 4 on right -->
        <div class="about-showcase">
            <div class="about-image-col">
                <div class="about-image-card">
                    <img src="{{ asset('logo/image-portal.webp') }}" alt="Logo POCH">
                </div>
            </div>

            <div class="about-content-col">
                <div class="about-paragraphs">
                    <div class="about-paragraph-item">
                        <div class="about-item-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <p>
                            {{ __('Através deste projeto, estamos comprometidos em promover o desenvolvimento humano e social,bem como estimular a competitividade das empresas do setor X. Com a nossa equipa multidisciplinar e especializada, estamos a trabalhar em estreita colaboração com as empresas para identificar as suas necessidades e criar soluções personalizadas que lhes permitam alcançar os seus objetivos.') }}
                        </p>
                    </div>

                    <div class="about-paragraph-item">
                        <div class="about-item-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="2" y1="12" x2="22" y2="12"></line>
                                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                            </svg>
                        </div>
                        <p>
                            {{ __('Estamos empenhados em garantir que este projeto seja bem-sucedido e contribua significativamente para o desenvolvimento económico e social da nossa região e do país como um todo.') }}
                        </p>
                    </div>

                    <div class="about-paragraph-item">
                        <div class="about-item-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            </svg>
                        </div>
                        <p>
                            {{ __('Convidamo-lo a conhecer os outros projetos que estamos a desenvolver em colaboração com o Programa Operacional Capital Humano. Visite o nosso website e explore as soluções inovadoras que estamos a criar em vários setores, desde a formação profissional à investigação e desenvolvimento. Junte-se a nós nesta jornada de inovação e progresso!') }}
                        </p>
                    </div>
                </div>

                <!-- Contact Button with exact original label -->
                <a href="{{ route('help.index') }}" class="btn-about-cta">
                    {{ __('Contato') }}
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </a>
            </div>
        </div>

        <!-- POCH Logos Image -->
        <div class="about-poch-card">
            <div class="about-poch-logo-wrap">
                <img src="{{ asset('logo/poch-logos.webp') }}" alt="Logotipos POCH">
            </div>
        </div>
    </div>
</x-app-layout>
