<div x-data x-init="$wire.loadServers">
    <div x-data="searchResources()">
        @if ($current_step === 'type')

            {{-- ============================================== --}}
            {{-- WIZARD SIMPLIFICADO PARA CLIENTES              --}}
            {{-- ============================================== --}}
            @if(!isEasytiAdmin())

                <div class="mb-8">
                    <h1 class="text-2xl font-bold dark:text-white">🚀 Criar Nova Aplicação</h1>
                    <p class="text-neutral-500 dark:text-neutral-400 mt-1">
                        Escolha o tipo de aplicação que você deseja criar
                    </p>
                </div>

                <!-- Indicador de Passos -->
                <div class="flex items-center justify-center mb-8">
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-[#4DC4E0] text-white flex items-center justify-center font-semibold text-sm">1</div>
                            <span class="text-sm font-medium dark:text-white">Tipo</span>
                        </div>
                        <div class="w-12 h-0.5 bg-neutral-300 dark:bg-coolgray-300"></div>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-neutral-200 dark:bg-coolgray-300 text-neutral-500 flex items-center justify-center font-semibold text-sm">2</div>
                            <span class="text-sm text-neutral-500">Configurar</span>
                        </div>
                        <div class="w-12 h-0.5 bg-neutral-300 dark:bg-coolgray-300"></div>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-neutral-200 dark:bg-coolgray-300 text-neutral-500 flex items-center justify-center font-semibold text-sm">3</div>
                            <span class="text-sm text-neutral-500">Deploy</span>
                        </div>
                    </div>
                </div>

                <!-- Seletor de Ambiente (Simplificado) -->
                @if(count($environments) > 1)
                <div class="mb-6 max-w-md">
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                        Ambiente
                    </label>
                    <x-forms.select wire:model.live="selectedEnvironment">
                        @foreach ($environments as $environment)
                            <option value="{{ $environment->name }}">{{ $environment->name }}</option>
                        @endforeach
                    </x-forms.select>
                </div>
                @endif

                <div x-show="loading" class="text-center py-12">
                    <div class="inline-flex items-center gap-3 text-neutral-500">
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Carregando opções...
                    </div>
                </div>

                <div x-show="!loading" class="space-y-8">
                    
                    <!-- SEÇÃO: Templates Populares (Mais Usados) -->
                    <section>
                        <h2 class="text-lg font-semibold dark:text-white mb-4 flex items-center gap-2">
                            <span class="text-2xl">⭐</span> Templates Populares
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            
                            <!-- Next.js -->
                            <div @click="setType('public')" 
                                 :class="{ 'cursor-pointer': !selecting, 'cursor-not-allowed opacity-50': selecting }"
                                 class="group bg-white dark:bg-coolgray-100 rounded-xl border-2 border-neutral-200 dark:border-coolgray-200 hover:border-[#4DC4E0] p-6 transition-all hover:shadow-lg">
                                <div class="flex items-center gap-4 mb-3">
                                    <div class="w-12 h-12 rounded-lg bg-black flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M11.572 0c-.176 0-.31.001-.358.007a19.76 19.76 0 01-.364.033C7.443.346 4.25 2.185 2.228 5.012a11.875 11.875 0 00-2.119 5.243c-.096.659-.108.854-.108 1.747s.012 1.089.108 1.748c.652 4.506 3.86 8.292 8.209 9.695.779.25 1.6.422 2.534.525.363.04 1.935.04 2.299 0 1.611-.178 2.977-.577 4.323-1.264.207-.106.247-.134.219-.158-.02-.013-.9-1.193-1.955-2.62l-1.919-2.592-2.404-3.558a338.739 338.739 0 00-2.422-3.556c-.009-.002-.018 1.579-.023 3.51-.007 3.38-.01 3.515-.052 3.595a.426.426 0 01-.206.214c-.075.037-.14.044-.495.044H7.81l-.108-.068a.438.438 0 01-.157-.171l-.05-.106.006-4.703.007-4.705.072-.092a.645.645 0 01.174-.143c.096-.047.134-.051.54-.051.478 0 .558.018.682.154.035.038 1.337 1.999 2.895 4.361a10760.433 10760.433 0 004.735 7.17l1.9 2.879.096-.063a12.317 12.317 0 002.466-2.163 11.944 11.944 0 002.824-6.134c.096-.66.108-.854.108-1.748 0-.893-.012-1.088-.108-1.747-.652-4.506-3.859-8.292-8.208-9.695a12.597 12.597 0 00-2.499-.523A33.119 33.119 0 0011.572 0zm4.069 7.217c.347 0 .408.005.486.047a.473.473 0 01.237.277c.018.06.023 1.365.018 4.304l-.006 4.218-.744-1.14-.746-1.14v-3.066c0-1.982.01-3.097.023-3.15a.478.478 0 01.233-.296c.096-.05.13-.054.5-.054z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold dark:text-white group-hover:text-[#4DC4E0] transition-colors">Next.js</h3>
                                        <p class="text-sm text-neutral-500">Framework React</p>
                                    </div>
                                </div>
                                <p class="text-sm text-neutral-600 dark:text-neutral-400">
                                    Deploy de aplicações React com SSR, API Routes e muito mais.
                                </p>
                            </div>

                            <!-- NestJS -->
                            <div @click="setType('public')" 
                                 :class="{ 'cursor-pointer': !selecting, 'cursor-not-allowed opacity-50': selecting }"
                                 class="group bg-white dark:bg-coolgray-100 rounded-xl border-2 border-neutral-200 dark:border-coolgray-200 hover:border-[#4DC4E0] p-6 transition-all hover:shadow-lg">
                                <div class="flex items-center gap-4 mb-3">
                                    <div class="w-12 h-12 rounded-lg bg-[#E0234E] flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M14.131.047c-.173 0-.334.037-.483.087.316.21.49.49.576.806.007.043.019.074.025.117a.681.681 0 01.013.112c.024.545-.143.702-.237.93-.157.378-.142.727.063 1.072.091.157.122.328.044.512-.046.195-.12.383-.215.561.38.2.793.287 1.213.283.439-.002.871-.1 1.28-.27a1.3 1.3 0 00-.154-.161 1.186 1.186 0 01-.32-.54c-.197-.618.356-1.264.985-1.575.518-.257 1.213-.394 1.73-.18.378.156.642.477.753.888.063.238.103.484.124.729.035.434-.133.797-.39 1.127-.303.393-.72.673-1.18.853-.324.127-.664.21-1.012.247a3.27 3.27 0 01-.694-.002c-.038-.003-.076-.009-.114-.015-.037-.007-.073-.014-.11-.017a1.468 1.468 0 00-.13.015c-.108.014-.218.023-.326.03-.07.005-.142.01-.213.017-.248.024-.495.06-.74.11l-.191.042-.04.008-.087.02a3.93 3.93 0 00-.3.079 6.32 6.32 0 00-1.183.464 5.914 5.914 0 00-1.24.82 5.4 5.4 0 00-.474.45c-.062.068-.124.136-.182.208l-.067.083-.035.044-.028.037c-.127.172-.244.352-.352.537-.095.164-.183.333-.26.506a5.08 5.08 0 00-.258.697c-.044.161-.08.325-.108.49a4.417 4.417 0 00-.054.375c-.004.038-.006.077-.008.116a4.826 4.826 0 00-.003.284c.003.12.012.241.027.36.008.063.018.125.03.186.011.061.024.122.04.183.007.03.015.06.024.09l.013.044.014.043c.02.062.042.123.067.183.024.06.05.12.08.178.031.06.066.117.103.173a.824.824 0 00.127.153c.021.02.042.04.065.058a.561.561 0 00.146.09 2.81 2.81 0 00.215.094c.063.024.127.044.192.062.065.018.13.033.196.045.066.012.133.02.2.026a1.476 1.476 0 00.206.006c.072-.002.144-.008.215-.017l.11-.017c.038-.007.077-.014.115-.023.078-.018.155-.04.23-.066.076-.026.15-.056.222-.09a1.65 1.65 0 00.212-.115c.07-.043.137-.09.203-.14.066-.05.13-.105.191-.162.062-.058.122-.119.179-.182.028-.032.056-.065.083-.098.028-.033.054-.067.08-.102.065-.086.126-.176.18-.27a3.27 3.27 0 00.283-.651 3.863 3.863 0 00.123-.71c.016-.12.025-.24.029-.36.003-.12.002-.24-.005-.36a3.867 3.867 0 00-.074-.707 3.46 3.46 0 00-.106-.38 2.855 2.855 0 00-.094-.262c-.037-.088-.08-.173-.126-.256a2.175 2.175 0 00-.16-.25 1.983 1.983 0 00-.206-.24 1.905 1.905 0 00-.251-.22 1.96 1.96 0 00-.294-.18 2.213 2.213 0 00-.335-.136 2.58 2.58 0 00-.373-.085 3.11 3.11 0 00-.405-.028h-.011l-.422.001c-.143.002-.285.009-.427.022a4.54 4.54 0 00-.422.05 4.478 4.478 0 00-.414.086c-.137.033-.273.071-.406.115a4.108 4.108 0 00-.39.146 3.736 3.736 0 00-.752.392 3.324 3.324 0 00-.335.256 2.99 2.99 0 00-.307.298c-.096.107-.186.22-.27.337a3.026 3.026 0 00-.22.372c-.032.066-.062.133-.089.201a3.22 3.22 0 00-.132.433 3.585 3.585 0 00-.082.455 3.932 3.932 0 00-.031.465c0 .157.007.314.022.47.016.155.04.31.071.462.032.153.071.303.118.451.047.148.1.294.161.435.062.141.131.279.206.412.076.133.158.261.247.384.089.123.184.241.285.353.101.111.207.217.32.316.112.1.23.192.353.278.123.086.25.164.382.235.132.07.269.133.409.187.14.054.284.1.43.137.147.037.296.066.447.086.15.02.302.032.455.035h.018c.15.003.3-.003.448-.017.15-.014.298-.036.445-.066a3.85 3.85 0 00.435-.114 3.64 3.64 0 00.418-.16 3.457 3.457 0 00.395-.203c.127-.076.25-.159.368-.248.118-.09.23-.187.337-.289.107-.103.208-.212.303-.326.095-.114.183-.234.264-.358.082-.124.156-.253.223-.385.067-.132.126-.268.177-.407.052-.14.096-.282.131-.427.036-.145.063-.293.082-.442.02-.149.03-.3.032-.451a4.007 4.007 0 00-.027-.456 3.874 3.874 0 00-.082-.45 3.563 3.563 0 00-.139-.432 3.315 3.315 0 00-.194-.405 3.11 3.11 0 00-.247-.369 2.926 2.926 0 00-.297-.327 2.794 2.794 0 00-.342-.28 2.727 2.727 0 00-.38-.227 2.774 2.774 0 00-.41-.171 2.974 2.974 0 00-.433-.112 3.362 3.362 0 00-.445-.05l-.45-.005c-.152.001-.305.009-.456.024a4.086 4.086 0 00-.442.068c-.143.03-.284.068-.421.112-.138.045-.272.096-.401.155-.13.059-.254.124-.373.195a3.242 3.242 0 00-.34.236c-.106.086-.206.178-.3.275a3.16 3.16 0 00-.26.313c-.079.11-.151.224-.216.342-.064.118-.121.24-.17.365-.049.125-.09.253-.123.383a3.397 3.397 0 00-.074.402 3.592 3.592 0 00-.024.408c.002.137.011.274.027.41.016.135.04.269.072.4.031.132.07.261.115.387.045.126.098.248.157.367.06.118.126.232.2.341.073.109.152.213.237.312.086.098.177.191.273.278.097.087.198.169.304.244.106.075.217.143.332.205.115.062.234.116.356.164.123.047.248.088.376.12.128.033.258.059.39.076.132.018.265.027.399.029h.013c.133.001.265-.005.396-.019.13-.014.26-.036.387-.064.128-.029.253-.065.375-.108.123-.043.242-.094.358-.15.116-.056.228-.12.336-.19.108-.07.212-.147.31-.23.099-.083.193-.172.281-.267.089-.094.172-.195.249-.3.077-.105.148-.216.212-.331.064-.115.122-.235.172-.358.05-.123.093-.249.129-.379.036-.129.064-.261.085-.394.02-.134.033-.269.038-.405.005-.136.002-.273-.009-.409a3.18 3.18 0 00-.06-.4 2.986 2.986 0 00-.11-.385 2.87 2.87 0 00-.158-.363 2.818 2.818 0 00-.204-.336 2.818 2.818 0 00-.247-.303 2.907 2.907 0 00-.285-.266 3.03 3.03 0 00-.32-.223 3.243 3.243 0 00-.35-.177 3.563 3.563 0 00-.377-.127 3.901 3.901 0 00-.394-.076 4.21 4.21 0 00-.406-.025c-.137 0-.274.006-.41.019a3.986 3.986 0 00-.402.058c-.131.027-.26.06-.385.101a3.353 3.353 0 00-.362.143c-.115.054-.225.115-.331.183-.106.067-.206.141-.301.222-.095.081-.185.168-.269.261-.084.093-.161.192-.232.296a2.998 2.998 0 00-.189.338c-.056.117-.105.238-.147.362-.042.125-.076.252-.102.382a3.141 3.141 0 00-.053.398c-.008.134-.01.268-.003.402.007.134.02.268.04.4.022.132.05.262.086.39.036.127.08.252.13.373.05.121.108.239.172.352.065.113.137.222.216.325.078.104.164.202.255.294.091.092.188.178.291.257.103.079.212.151.326.215.114.064.233.12.355.169.123.048.249.089.378.121.13.032.262.056.396.072.134.016.269.023.404.022.135-.001.27-.01.403-.027.134-.017.266-.043.396-.076.13-.033.257-.074.381-.122.124-.048.245-.103.362-.166.117-.062.229-.132.337-.208.108-.077.21-.16.307-.25.096-.089.187-.185.271-.285.084-.101.162-.207.232-.319.071-.111.134-.228.19-.348.056-.12.104-.245.144-.372.04-.127.073-.258.097-.39.025-.133.041-.267.05-.403.008-.135.008-.271 0-.406a3.099 3.099 0 00-.052-.403 3.002 3.002 0 00-.1-.388 2.925 2.925 0 00-.149-.368 2.884 2.884 0 00-.196-.342 2.89 2.89 0 00-.239-.31 2.955 2.955 0 00-.28-.275 3.068 3.068 0 00-.315-.235 3.255 3.255 0 00-.346-.193 3.523 3.523 0 00-.372-.147 3.847 3.847 0 00-.392-.1 4.099 4.099 0 00-.405-.054 4.092 4.092 0 00-.411-.008c-.137.003-.274.014-.409.032-.135.019-.268.045-.399.079-.13.034-.258.076-.382.124a3.209 3.209 0 00-.36.172 2.996 2.996 0 00-.331.218 2.842 2.842 0 00-.297.261 2.752 2.752 0 00-.259.3 2.713 2.713 0 00-.217.336 2.72 2.72 0 00-.171.367 2.8 2.8 0 00-.122.392 2.974 2.974 0 00-.07.411 3.21 3.21 0 00-.015.422c.005.141.017.281.038.42.02.138.049.274.086.408.037.133.082.263.135.39.053.127.114.25.183.368.068.118.145.231.228.34.084.108.175.211.273.307.098.097.202.187.312.271.11.084.226.161.347.23.12.069.247.13.377.183.13.053.264.098.401.134.138.036.278.063.42.082.142.019.285.029.429.031h.011c.144.002.287-.005.43-.019.142-.015.283-.037.422-.067.14-.03.276-.068.41-.113.133-.045.263-.098.39-.158.126-.06.249-.128.367-.203.118-.074.231-.156.34-.244.108-.088.211-.182.308-.283.097-.1.188-.207.273-.319.084-.112.162-.23.232-.353.07-.123.133-.251.188-.382.055-.132.102-.267.14-.405.039-.138.069-.279.092-.421.022-.143.036-.286.042-.431.006-.145.004-.29-.008-.434a3.117 3.117 0 00-.061-.427 2.985 2.985 0 00-.113-.41 2.884 2.884 0 00-.163-.387 2.818 2.818 0 00-.21-.357 2.788 2.788 0 00-.255-.322 2.812 2.812 0 00-.295-.282 2.9 2.9 0 00-.331-.238 3.06 3.06 0 00-.361-.19 3.295 3.295 0 00-.386-.14 3.565 3.565 0 00-.404-.089 3.799 3.799 0 00-.416-.038h-.421c-.14.005-.279.016-.417.035-.138.019-.274.045-.408.079-.134.034-.265.075-.393.123-.128.048-.253.104-.374.166-.12.062-.237.132-.35.208-.112.076-.22.16-.322.25-.103.09-.2.186-.291.289-.091.102-.177.21-.255.324-.078.114-.15.233-.214.357-.064.124-.121.252-.17.384-.05.132-.091.267-.124.404-.033.138-.058.278-.074.42-.017.141-.025.284-.025.427.001.142.01.285.027.426.017.142.043.282.077.42.034.138.076.274.126.406.05.132.108.261.173.386.065.125.137.245.217.36.079.116.165.226.257.33.093.105.191.203.296.295.104.092.214.177.33.255.115.078.235.149.36.212.125.063.254.118.386.166.132.047.268.087.406.118.138.031.279.054.421.069.142.015.285.021.428.019.143-.002.286-.012.427-.031.142-.019.281-.046.419-.081.137-.035.272-.078.403-.129.131-.05.259-.109.382-.175.123-.066.242-.14.356-.22.114-.08.222-.167.325-.261.103-.094.2-.195.29-.301.09-.106.174-.218.251-.335.077-.117.146-.24.209-.366.062-.127.117-.258.163-.392.047-.134.085-.271.115-.41.03-.139.051-.28.064-.422.012-.142.016-.285.011-.428a3.082 3.082 0 00-.042-.422 2.978 2.978 0 00-.092-.408 2.892 2.892 0 00-.14-.386 2.827 2.827 0 00-.187-.36 2.78 2.78 0 00-.23-.326 2.78 2.78 0 00-.27-.289 2.842 2.842 0 00-.306-.247 2.975 2.975 0 00-.338-.202 3.173 3.173 0 00-.365-.155 3.444 3.444 0 00-.386-.106 3.76 3.76 0 00-.401-.057 4.006 4.006 0 00-.41-.008h-.412c-.137.008-.274.022-.408.043-.135.022-.268.05-.399.086-.131.036-.259.079-.383.13-.124.05-.245.108-.361.172-.117.064-.229.136-.336.214-.107.079-.209.164-.305.255-.096.092-.186.19-.27.294-.083.103-.16.212-.23.326-.07.114-.133.233-.19.356-.056.123-.105.25-.146.38-.041.13-.075.264-.1.399-.027.135-.045.272-.056.41a3.144 3.144 0 00-.01.416c.006.138.02.276.042.412.022.136.052.27.09.402.038.131.084.26.137.385.053.125.114.246.183.362.068.116.144.227.226.333.083.106.172.206.268.3.096.094.198.182.306.262.108.08.22.154.337.22.118.066.24.124.365.175.126.05.254.093.385.128.131.035.265.062.4.08.135.019.272.03.408.032h.011c.136.002.272-.004.407-.019.135-.014.268-.037.399-.067.13-.03.259-.067.384-.113.125-.045.247-.098.365-.158.117-.061.23-.129.339-.204.108-.075.212-.157.311-.245.098-.088.191-.183.278-.283.087-.1.168-.206.243-.317.074-.111.142-.228.203-.349.06-.12.114-.245.16-.373.047-.128.086-.259.117-.393.031-.133.055-.269.071-.405.016-.137.024-.275.024-.413 0-.138-.008-.276-.024-.413a2.919 2.919 0 00-.071-.405 2.843 2.843 0 00-.117-.392 2.777 2.777 0 00-.16-.373 2.727 2.727 0 00-.203-.35 2.699 2.699 0 00-.243-.316 2.71 2.71 0 00-.278-.284 2.78 2.78 0 00-.31-.244 2.909 2.909 0 00-.34-.204 3.1 3.1 0 00-.365-.158 3.37 3.37 0 00-.384-.113 3.667 3.667 0 00-.399-.067 3.91 3.91 0 00-.407-.019h-.413c-.135.002-.27.013-.403.032-.133.018-.265.045-.394.08-.129.035-.255.078-.377.128-.123.05-.241.108-.355.173-.114.065-.224.137-.329.215-.104.078-.203.163-.297.254-.093.091-.181.188-.262.29-.081.102-.156.21-.224.323-.068.112-.129.229-.183.35-.054.12-.101.245-.14.372-.04.127-.072.257-.096.389-.024.132-.04.266-.048.4-.008.135-.008.27 0 .405.008.134.024.268.048.4.024.132.056.262.096.389.04.127.086.252.14.372.054.121.115.238.183.35.068.112.143.22.224.322.081.102.169.199.262.29.094.091.193.176.297.254.105.078.215.15.329.215.114.065.232.123.355.173.122.05.248.093.377.128.129.035.26.062.394.08.133.019.268.03.403.032z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold dark:text-white group-hover:text-[#4DC4E0] transition-colors">NestJS</h3>
                                        <p class="text-sm text-neutral-500">Backend Node.js</p>
                                    </div>
                                </div>
                                <p class="text-sm text-neutral-600 dark:text-neutral-400">
                                    Framework progressivo para APIs robustas em TypeScript.
                                </p>
                            </div>

                            <!-- Node.js -->
                            <div @click="setType('public')" 
                                 :class="{ 'cursor-pointer': !selecting, 'cursor-not-allowed opacity-50': selecting }"
                                 class="group bg-white dark:bg-coolgray-100 rounded-xl border-2 border-neutral-200 dark:border-coolgray-200 hover:border-[#4DC4E0] p-6 transition-all hover:shadow-lg">
                                <div class="flex items-center gap-4 mb-3">
                                    <div class="w-12 h-12 rounded-lg bg-[#339933] flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M11.998,24c-0.321,0-0.641-0.084-0.922-0.247l-2.936-1.737c-0.438-0.245-0.224-0.332-0.08-0.383 c0.585-0.203,0.703-0.25,1.328-0.604c0.065-0.037,0.151-0.023,0.218,0.017l2.256,1.339c0.082,0.045,0.197,0.045,0.272,0l8.795-5.076 c0.082-0.047,0.134-0.141,0.134-0.238V6.921c0-0.099-0.053-0.192-0.137-0.242l-8.791-5.072c-0.081-0.047-0.189-0.047-0.271,0 L3.075,6.68C2.99,6.729,2.936,6.825,2.936,6.921v10.15c0,0.097,0.054,0.189,0.139,0.235l2.409,1.392 c1.307,0.654,2.108-0.116,2.108-0.89V7.787c0-0.142,0.114-0.253,0.256-0.253h1.115c0.139,0,0.255,0.112,0.255,0.253v10.021 c0,1.745-0.95,2.745-2.604,2.745c-0.508,0-0.909,0-2.026-0.551L2.28,18.675c-0.57-0.329-0.922-0.945-0.922-1.604V6.921 c0-0.659,0.353-1.275,0.922-1.603l8.795-5.082c0.557-0.315,1.296-0.315,1.848,0l8.794,5.082c0.57,0.329,0.924,0.944,0.924,1.603 v10.15c0,0.659-0.354,1.273-0.924,1.604l-8.794,5.078C12.643,23.916,12.324,24,11.998,24z M19.099,13.993 c0-1.9-1.284-2.406-3.987-2.763c-2.731-0.361-3.009-0.548-3.009-1.187c0-0.528,0.235-1.233,2.258-1.233 c1.807,0,2.473,0.389,2.747,1.607c0.024,0.115,0.129,0.199,0.247,0.199h1.141c0.071,0,0.138-0.031,0.186-0.081 c0.048-0.054,0.074-0.123,0.067-0.196c-0.177-2.098-1.571-3.076-4.388-3.076c-2.508,0-4.004,1.058-4.004,2.833 c0,1.925,1.488,2.457,3.895,2.695c2.88,0.282,3.103,0.703,3.103,1.269c0,0.983-0.789,1.402-2.642,1.402 c-2.327,0-2.839-0.584-3.011-1.742c-0.02-0.124-0.126-0.215-0.253-0.215h-1.137c-0.141,0-0.254,0.112-0.254,0.253 c0,1.482,0.806,3.248,4.655,3.248C17.501,17.007,19.099,15.91,19.099,13.993z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold dark:text-white group-hover:text-[#4DC4E0] transition-colors">Node.js</h3>
                                        <p class="text-sm text-neutral-500">JavaScript Runtime</p>
                                    </div>
                                </div>
                                <p class="text-sm text-neutral-600 dark:text-neutral-400">
                                    Aplicações Express, Fastify ou qualquer projeto Node.js.
                                </p>
                            </div>

                        </div>
                    </section>

                    <!-- SEÇÃO: Mais Opções -->
                    <section>
                        <h2 class="text-lg font-semibold dark:text-white mb-4 flex items-center gap-2">
                            <span class="text-2xl">📦</span> Outras Opções
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            
                            <!-- Repositório Git Público -->
                            <div @click="setType('public')" 
                                 :class="{ 'cursor-pointer': !selecting, 'cursor-not-allowed opacity-50': selecting }"
                                 class="group bg-white dark:bg-coolgray-100 rounded-xl border border-neutral-200 dark:border-coolgray-200 hover:border-[#4DC4E0] p-5 transition-all">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-10 h-10 rounded-lg bg-neutral-100 dark:bg-coolgray-200 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-neutral-600 dark:text-neutral-400" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                        </svg>
                                    </div>
                                    <h3 class="font-medium dark:text-white text-sm">Repositório Git</h3>
                                </div>
                                <p class="text-xs text-neutral-500">GitHub, GitLab, Bitbucket...</p>
                            </div>

                            <!-- Docker Compose -->
                            <div @click="setType('docker-compose-empty')" 
                                 :class="{ 'cursor-pointer': !selecting, 'cursor-not-allowed opacity-50': selecting }"
                                 class="group bg-white dark:bg-coolgray-100 rounded-xl border border-neutral-200 dark:border-coolgray-200 hover:border-[#4DC4E0] p-5 transition-all">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-10 h-10 rounded-lg bg-[#2496ED]/10 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-[#2496ED]" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M13.983 11.078h2.119a.186.186 0 00.186-.185V9.006a.186.186 0 00-.186-.186h-2.119a.185.185 0 00-.185.185v1.888c0 .102.083.185.185.185m-2.954-5.43h2.118a.186.186 0 00.186-.186V3.574a.186.186 0 00-.186-.185h-2.118a.185.185 0 00-.185.185v1.888c0 .102.082.185.185.185m0 2.716h2.118a.187.187 0 00.186-.186V6.29a.186.186 0 00-.186-.185h-2.118a.185.185 0 00-.185.185v1.887c0 .102.082.185.185.186m-2.93 0h2.12a.186.186 0 00.184-.186V6.29a.185.185 0 00-.185-.185H8.1a.185.185 0 00-.185.185v1.887c0 .102.083.185.185.186m-2.964 0h2.119a.186.186 0 00.185-.186V6.29a.185.185 0 00-.185-.185H5.136a.186.186 0 00-.186.185v1.887c0 .102.084.185.186.186m5.893 2.715h2.118a.186.186 0 00.186-.185V9.006a.186.186 0 00-.186-.186h-2.118a.185.185 0 00-.185.185v1.888c0 .102.082.185.185.185m-2.93 0h2.12a.185.185 0 00.184-.185V9.006a.185.185 0 00-.184-.186h-2.12a.185.185 0 00-.184.185v1.888c0 .102.083.185.185.185m-2.964 0h2.119a.185.185 0 00.185-.185V9.006a.185.185 0 00-.184-.186h-2.12a.186.186 0 00-.186.186v1.887c0 .102.084.185.186.185m-2.92 0h2.12a.185.185 0 00.184-.185V9.006a.185.185 0 00-.184-.186h-2.12a.185.185 0 00-.184.185v1.888c0 .102.082.185.185.185M23.763 9.89c-.065-.051-.672-.51-1.954-.51-.338.001-.676.03-1.01.087-.248-1.7-1.653-2.53-1.716-2.566l-.344-.199-.226.327c-.284.438-.49.922-.612 1.43-.23.97-.09 1.882.403 2.661-.595.332-1.55.413-1.744.42H.751a.751.751 0 00-.75.748 11.376 11.376 0 00.692 4.062c.545 1.428 1.355 2.48 2.41 3.124 1.18.723 3.1 1.137 5.275 1.137.983.003 1.963-.086 2.93-.266a12.248 12.248 0 003.823-1.389c.98-.567 1.86-1.288 2.61-2.136 1.252-1.418 1.998-2.997 2.553-4.4h.221c1.372 0 2.215-.549 2.68-1.009.309-.293.55-.65.707-1.046l.098-.288Z"/>
                                        </svg>
                                    </div>
                                    <h3 class="font-medium dark:text-white text-sm">Docker Compose</h3>
                                </div>
                                <p class="text-xs text-neutral-500">Multi-container</p>
                            </div>

                            <!-- Docker Image -->
                            <div @click="setType('docker-image')" 
                                 :class="{ 'cursor-pointer': !selecting, 'cursor-not-allowed opacity-50': selecting }"
                                 class="group bg-white dark:bg-coolgray-100 rounded-xl border border-neutral-200 dark:border-coolgray-200 hover:border-[#4DC4E0] p-5 transition-all">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-10 h-10 rounded-lg bg-[#2496ED]/10 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-[#2496ED]" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M13.983 11.078h2.119a.186.186 0 00.186-.185V9.006a.186.186 0 00-.186-.186h-2.119a.185.185 0 00-.185.185v1.888c0 .102.083.185.185.185m-2.954-5.43h2.118a.186.186 0 00.186-.186V3.574a.186.186 0 00-.186-.185h-2.118a.185.185 0 00-.185.185v1.888c0 .102.082.185.185.185"/>
                                        </svg>
                                    </div>
                                    <h3 class="font-medium dark:text-white text-sm">Imagem Docker</h3>
                                </div>
                                <p class="text-xs text-neutral-500">Do Docker Hub</p>
                            </div>

                            <!-- Dockerfile -->
                            <div @click="setType('dockerfile')" 
                                 :class="{ 'cursor-pointer': !selecting, 'cursor-not-allowed opacity-50': selecting }"
                                 class="group bg-white dark:bg-coolgray-100 rounded-xl border border-neutral-200 dark:border-coolgray-200 hover:border-[#4DC4E0] p-5 transition-all">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-10 h-10 rounded-lg bg-neutral-100 dark:bg-coolgray-200 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-neutral-600 dark:text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                        </svg>
                                    </div>
                                    <h3 class="font-medium dark:text-white text-sm">Dockerfile</h3>
                                </div>
                                <p class="text-xs text-neutral-500">Build personalizado</p>
                            </div>

                        </div>
                    </section>

                    <!-- Link para ver todos os serviços -->
                    <div class="text-center pt-4">
                        <a href="#all-services" 
                           @click.prevent="search = ''; selectedCategory = ''"
                           class="text-[#4DC4E0] hover:underline text-sm font-medium">
                            Ver mais de 200+ serviços disponíveis →
                        </a>
                    </div>
                </div>

            {{-- ============================================== --}}
            {{-- SELEÇÃO ORIGINAL PARA ADMIN                    --}}
            {{-- ============================================== --}}
            @else

            <div x-init="window.addEventListener('scroll', () => isSticky = window.pageYOffset > 100)"
                class="sticky z-10 top-0  backdrop-blur-sm border-b border-neutral-200 dark:border-coolgray-400">
                <div class="flex flex-col gap-4 lg:flex-row">
                    <h1>Novo Recurso</h1>
                    <div class="w-full lg:w-96">
                        <x-forms.select wire:model.live="selectedEnvironment">
                            @foreach ($environments as $environment)
                                <option value="{{ $environment->name }}">Ambiente: {{ $environment->name }}</option>
                            @endforeach
                        </x-forms.select>
                    </div>
                </div>
                <div class="mb-4">Deploy de recursos: Aplicações, Databases, Services...</div>
                <div class="flex gap-2 items-start">
                    <input autocomplete="off" x-ref="searchInput" class="input-sticky flex-1"
                        :class="{ 'input-sticky-active': isSticky }" x-model="search" placeholder="Buscar..."
                        @keydown.window.slash.prevent="$refs.searchInput.focus()">
                    <!-- Category Filter Dropdown -->
                    <div class="relative" x-data="{ openCategoryDropdown: false, categorySearch: '' }" @click.outside="openCategoryDropdown = false">
                        <!-- Loading/Disabled State -->
                        <div x-show="loading || categories.length === 0"
                            class="flex items-center justify-between gap-2 py-1.5 px-3 w-64 text-sm rounded-sm border-0 ring-2 ring-inset ring-neutral-200 dark:ring-coolgray-300 bg-neutral-100 dark:bg-coolgray-200 cursor-not-allowed whitespace-nowrap opacity-50">
                            <span class="text-sm text-neutral-400 dark:text-neutral-600">Filtrar por categoria</span>
                            <svg class="w-4 h-4 text-neutral-400 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                        <!-- Active State -->
                        <div x-show="!loading && categories.length > 0"
                            @click="openCategoryDropdown = !openCategoryDropdown; $nextTick(() => { if (openCategoryDropdown) $refs.categorySearchInput.focus() })"
                            class="flex items-center justify-between gap-2 py-1.5 px-3 w-64 text-sm rounded-sm border-0 ring-2 ring-inset ring-neutral-200 dark:ring-coolgray-300 bg-white dark:bg-coolgray-100 cursor-pointer hover:ring-coolgray-400 transition-all whitespace-nowrap">
                            <span class="text-sm truncate"
                                x-text="selectedCategory === '' ? 'Filtrar por categoria' : selectedCategory"
                                :class="selectedCategory === '' ? 'text-neutral-400 dark:text-neutral-600' :
                                    'capitalize text-black dark:text-white'"></span>
                            <svg class="w-4 h-4 transition-transform text-neutral-400 shrink-0"
                                :class="{ 'rotate-180': openCategoryDropdown }" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                        <!-- Dropdown Menu -->
                        <div x-show="openCategoryDropdown" x-transition
                            class="absolute z-50 w-full mt-1 bg-white dark:bg-coolgray-100 border border-neutral-300 dark:border-coolgray-400 rounded shadow-lg overflow-hidden">
                            <div
                                class="sticky top-0 p-2 bg-white dark:bg-coolgray-100 border-b border-neutral-300 dark:border-coolgray-400">
                                <input type="text" x-ref="categorySearchInput" x-model="categorySearch"
                                    placeholder="Buscar categorias..."
                                    class="w-full px-2 py-1 text-sm rounded border border-neutral-300 dark:border-coolgray-400 bg-white dark:bg-coolgray-200 focus:outline-none focus:ring-2 focus:ring-coolgray-400"
                                    @click.stop>
                            </div>
                            <div class="max-h-60 overflow-auto scrollbar">
                                <div @click="selectedCategory = ''; categorySearch = ''; openCategoryDropdown = false"
                                    class="px-3 py-2 cursor-pointer hover:bg-neutral-100 dark:hover:bg-coolgray-200"
                                    :class="{ 'bg-neutral-50 dark:bg-coolgray-300': selectedCategory === '' }">
                                    <span class="text-sm">Todas as Categorias</span>
                                </div>
                                <template
                                    x-for="category in categories.filter(cat => categorySearch === '' || cat.toLowerCase().includes(categorySearch.toLowerCase()))"
                                    :key="category">
                                    <div @click="selectedCategory = category; categorySearch = ''; openCategoryDropdown = false"
                                        class="px-3 py-2 cursor-pointer hover:bg-neutral-100 dark:hover:bg-coolgray-200 capitalize"
                                        :class="{ 'bg-neutral-50 dark:bg-coolgray-300': selectedCategory === category }">
                                        <span class="text-sm" x-text="category"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div x-show="loading">Carregando...</div>
            <div x-show="!loading" class="flex flex-col gap-4 py-4">
                <h2 x-show="filteredGitBasedApplications.length > 0">Aplicações</h2>
                <div x-show="filteredGitBasedApplications.length > 0 || filteredDockerBasedApplications.length > 0"
                    class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div x-show="filteredGitBasedApplications.length > 0" class="space-y-4">
                        <h4>Baseadas em Git</h4>
                        <div class="grid justify-start grid-cols-1 gap-4 text-left">
                            <template x-for="application in filteredGitBasedApplications" :key="application.name">
                                <div x-on:click='setType(application.id)'
                                    :class="{ 'cursor-pointer': !selecting, 'cursor-not-allowed opacity-50': selecting }">
                                    <x-resource-view>
                                        <x-slot:title><span x-text="application.name"></span></x-slot>
                                        <x-slot:description>
                                            <span x-html="window.sanitizeHTML(application.description)"></span>
                                        </x-slot>
                                        <x-slot:logo>
                                            <img class="w-full h-full p-2 transition-all duration-200 dark:bg-white/10 bg-black/10 object-contain"
                                                :src="application.logo">
                                        </x-slot:logo>
                                    </x-resource-view>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div x-show="filteredDockerBasedApplications.length > 0" class="space-y-4">
                        <h4>Baseadas em Docker</h4>
                        <div class="grid justify-start grid-cols-1 gap-4 text-left">
                            <template x-for="application in filteredDockerBasedApplications" :key="application.name">
                                <div x-on:click="setType(application.id)"
                                    :class="{ 'cursor-pointer': !selecting, 'cursor-not-allowed opacity-50': selecting }">
                                    <x-resource-view>
                                        <x-slot:title><span x-text="application.name"></span></x-slot>
                                        <x-slot:description><span x-text="application.description"></span></x-slot>
                                        <x-slot:logo> <img
                                                class="w-full h-full p-2 transition-all duration-200 dark:bg-white/10 bg-black/10 object-contain"
                                                :src="application.logo"></x-slot>
                                    </x-resource-view>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                <div x-show="filteredDatabases.length > 0" class="mt-8">
                    <h2 class="mb-4">Bancos de Dados</h2>
                    <div class="grid justify-start grid-cols-1 gap-4 text-left xl:grid-cols-3">
                        <template x-for="database in filteredDatabases" :key="database.id">
                            <div x-on:click="setType(database.id)"
                                :class="{ 'cursor-pointer': !selecting, 'cursor-not-allowed opacity-50': selecting }">
                                <x-resource-view>
                                    <x-slot:title><span x-text="database.name"></span></x-slot>
                                    <x-slot:description><span x-text="database.description"></span></x-slot>
                                    <x-slot:logo>
                                        <span x-show="database.logo">
                                            <span x-html="database.logo"></span>
                                        </span>
                                    </x-slot>
                                </x-resource-view>
                            </div>
                        </template>
                    </div>
                </div>
                <div x-show="filteredServices.length > 0" class="mt-8" id="all-services">
                    <div class="flex items-center gap-4" x-init="loadResources">
                        <h2>Serviços</h2>
                        <x-forms.button x-on:click="loadResources">Recarregar</x-forms.button>
                    </div>
                    <div class="grid justify-start grid-cols-1 gap-4 text-left xl:grid-cols-3 mt-4">
                        <template x-for="service in filteredServices" :key="service.name">
                            <div class="relative" x-on:click="setType('one-click-service-' + service.name)"
                                :class="{ 'cursor-pointer': !selecting, 'cursor-not-allowed opacity-50': selecting }">
                                <x-resource-view>
                                    <x-slot:title>
                                        <template x-if="service.name">
                                            <span x-text="service.name"></span>
                                        </template>
                                    </x-slot>
                                    <x-slot:description>
                                        <template x-if="service.slogan">
                                            <span x-text="service.slogan"></span>
                                        </template>
                                    </x-slot>
                                    <x-slot:logo>
                                        <template x-if="service.logo">
                                            <img class="w-full h-full p-2 transition-all duration-200 dark:bg-white/10 bg-black/10 object-contain"
                                                :src='service.logo'
                                                onerror="this.onerror=null; this.src='/coolify-logo.svg';" />
                                        </template>
                                    </x-slot:logo>
                                </x-resource-view>
                            </div>
                        </template>
                    </div>
                </div>
                <div
                    x-show="filteredGitBasedApplications.length === 0 && filteredDockerBasedApplications.length === 0 && filteredDatabases.length === 0 && filteredServices.length === 0 && loading === false">
                    <div>Nenhum recurso encontrado.</div>
                </div>
            </div>

            @endif

            <script>
                function sortFn(a, b) {
                    return a.name.localeCompare(b.name)
                }

                function searchResources() {
                    return {
                        search: '',
                        selectedCategory: '',
                        categories: [],
                        loading: false,
                        isSticky: false,
                        selecting: false,
                        services: [],
                        gitBasedApplications: [],
                        dockerBasedApplications: [],
                        databases: [],
                        setType(type) {
                            if (this.selecting) return;
                            this.selecting = true;
                            this.$wire.setType(type);
                        },
                        async loadResources() {
                            this.loading = true;
                            const {
                                services,
                                categories,
                                gitBasedApplications,
                                dockerBasedApplications,
                                databases
                            } = await this.$wire.loadServices();
                            this.services = services;
                            this.categories = categories || [];
                            this.gitBasedApplications = gitBasedApplications;
                            this.dockerBasedApplications = dockerBasedApplications;
                            this.databases = databases;
                            this.loading = false;
                        },
                        filterAndSort(items, isSort = true) {
                            const searchLower = this.search.trim().toLowerCase();
                            let filtered = Object.values(items);

                            if (this.selectedCategory !== '') {
                                const selectedCategoryLower = this.selectedCategory.toLowerCase();
                                filtered = filtered.filter(item => {
                                    if (!item.category) return false;
                                    const categories = item.category.includes(',') ?
                                        item.category.split(',').map(c => c.trim().toLowerCase()) : [item.category.toLowerCase()];
                                    return categories.includes(selectedCategoryLower);
                                });
                            }

                            if (searchLower !== '') {
                                filtered = filtered.filter(item => {
                                    return (item.name?.toLowerCase().includes(searchLower) ||
                                        item.description?.toLowerCase().includes(searchLower) ||
                                        item.slogan?.toLowerCase().includes(searchLower))
                                });
                            }

                            return isSort ? filtered.sort(sortFn) : filtered;
                        },
                        get filteredGitBasedApplications() {
                            if (this.gitBasedApplications.length === 0) return [];
                            return [this.gitBasedApplications].flatMap((items) => this.filterAndSort(items, false));
                        },
                        get filteredDockerBasedApplications() {
                            if (this.dockerBasedApplications.length === 0) return [];
                            return [this.dockerBasedApplications].flatMap((items) => this.filterAndSort(items, false));
                        },
                        get filteredDatabases() {
                            if (this.databases.length === 0) return [];
                            return [this.databases].flatMap((items) => this.filterAndSort(items, false));
                        },
                        get filteredServices() {
                            if (this.services.length === 0) return [];
                            return [this.services].flatMap((items) => this.filterAndSort(items, true));
                        }
                    }
                }
            </script>
        @endif
    </div>
    @if ($current_step === 'servers')
        <h2>Selecione um servidor</h2>
        <div class="pb-5"></div>
        <div class="flex flex-col justify-center gap-4 text-left xl:flex-row xl:flex-wrap">
            @if ($onlyBuildServerAvailable)
                <div> Apenas servidores de build disponíveis. <a class="underline dark:text-white" href="/servers" {{ wireNavigate() }}>
                    Ir para servidores
                </a> </div>
            @else
                @forelse($servers as $server)
                    <div class="w-full coolbox group" wire:click="setServer({{ $server }})">
                        <div class="flex flex-col mx-6">
                            <div class="box-title">{{ $server->name }}</div>
                            <div class="box-description">{{ $server->description }}</div>
                        </div>
                    </div>
                @empty
                    <div>
                        <div>Nenhum servidor encontrado. <a class="underline dark:text-white" href="/servers" {{ wireNavigate() }}>
                            Ir para servidores
                        </a></div>
                    </div>
                @endforelse
            @endif
        </div>
    @endif
    @if ($current_step === 'destinations')
        <h2>Selecione um destino</h2>
        <div class="pb-4">Destinos são usados para segregar recursos por rede.</div>
        <div class="flex flex-col justify-center gap-4 text-left xl:flex-row xl:flex-wrap">
            @if ($server->isSwarm())
                @foreach ($swarmDockers as $swarmDocker)
                    <div class="w-full coolbox group" wire:click="setDestination('{{ $swarmDocker->uuid }}')">
                        <div class="flex flex-col mx-6">
                            <div class="font-bold dark:group-hover:text-white">
                                Swarm Docker <span class="text-xs">({{ $swarmDocker->name }})</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                @foreach ($standaloneDockers as $standaloneDocker)
                    <div class="w-full coolbox group" wire:click="setDestination('{{ $standaloneDocker->uuid }}')">
                        <div class="flex flex-col mx-6">
                            <div class="box-title">
                                Standalone Docker <span class="text-xs">({{ $standaloneDocker->name }})</span>
                            </div>
                            <div class="box-description">Rede: {{ $standaloneDocker->network }}</div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    @endif
    @if ($current_step === 'select-postgresql-type')
        <div x-data="{ selecting: false }">
            <h2>Selecione o tipo de PostgreSQL</h2>
            <div>Se precisar de extensões extras, selecione Supabase PostgreSQL.</div>
            <div class="flex flex-col gap-6 pt-8">
                <div class="gap-2 coolbox group flex relative"
                    :class="{ 'cursor-pointer': !selecting, 'cursor-not-allowed opacity-50': selecting }"
                    x-on:click="!selecting && (selecting = true, $wire.setPostgresqlType('postgres:17-alpine'))">
                    <div class="flex flex-col">
                        <div class="box-title">PostgreSQL 17 (padrão)</div>
                        <div class="box-description">
                            PostgreSQL padrão, poderoso e open-source.
                        </div>
                    </div>
                </div>
                <div class="gap-2 coolbox group flex relative"
                    :class="{ 'cursor-pointer': !selecting, 'cursor-not-allowed opacity-50': selecting }"
                    x-on:click="!selecting && (selecting = true, $wire.setPostgresqlType('supabase/postgres:17.4.1.032'))">
                    <div class="flex flex-col">
                        <div class="box-title">Supabase PostgreSQL (com extensões)</div>
                        <div class="box-description">
                            PostgreSQL com várias extensões do Supabase.
                        </div>
                    </div>
                </div>
                <div class="gap-2 coolbox group flex relative"
                    :class="{ 'cursor-pointer': !selecting, 'cursor-not-allowed opacity-50': selecting }"
                    x-on:click="!selecting && (selecting = true, $wire.setPostgresqlType('postgis/postgis:17-3.5-alpine'))">
                    <div class="flex flex-col">
                        <div class="box-title">PostGIS (apenas AMD)</div>
                        <div class="box-description">
                            PostgreSQL com extensão geográfica.
                        </div>
                    </div>
                </div>
                <div class="gap-2 coolbox group flex relative"
                    :class="{ 'cursor-pointer': !selecting, 'cursor-not-allowed opacity-50': selecting }"
                    x-on:click="!selecting && (selecting = true, $wire.setPostgresqlType('pgvector/pgvector:pg17'))">
                    <div class="flex flex-col">
                        <div class="box-title">PGVector (17)</div>
                        <div class="box-description">
                            PostgreSQL com suporte a vetores (IA/ML).
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if ($current_step === 'existing-postgresql')
        <form wire:submit='addExistingPostgresql' class="flex items-end gap-4">
            <x-forms.input placeholder="postgres://usuario:senha@banco:5432" label="URL do Banco" id="existingPostgresqlUrl" />
            <x-forms.button type="submit">Adicionar Banco</x-forms.button>
        </form>
    @endif
</div>
