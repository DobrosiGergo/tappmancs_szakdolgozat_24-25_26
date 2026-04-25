<div wire:ignore.self>
    @if($isExpanded)
        <div class="w-64 h-screen bg-white shadow-lg transition-all duration-300 fixed top-0 left-0 z-10">
            <div class="flex items-center p-4 border-b">
                <div class="mr-3">
                    <img src="{{ asset('images/pet-svgrepo-com.svg') }}" class="h-10 w-10">
                </div>
                <h1 class="text-xl font-semibold text-gray-800">Tappmancs</h1>
                <button wire:click="toggleSidebar" class="ml-auto text-[#333333] hover:text-blue-500 focus:outline-none">
                   <- 
                </button>
            </div>
            
            <nav class="py-4">
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 hover:bg-blue-200 hover:rounded-lg text-[#333333]">
                    <img src="{{ asset('images/home.svg') }}" class="h-5 w-5 mr-2">
                    <span>Főoldal</span>
                </a>
                <a href="{{ route('shelters.index') }}" class="flex items-center px-4 py-2 text-[#333333] hover:bg-blue-200 hover:rounded-lg">
                    <img src="{{ asset('images/pet-shelter-svgrepo-com.svg') }}" class="h-5 w-5 mr-2">
                    <span>Menhelyek</span>
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-[#333333] hover:bg-blue-200 hover:rounded-lg">
                    <img src="{{ asset('images/pet-svgrepo-com.svg') }}" class="h-5 w-5 mr-2">
                    <span>Kisállatok</span>
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-[#333333] hover:bg-blue-200 hover:rounded-lg">
                    <img src="{{ asset('images/search.svg') }}" alt="" class="h-5 w-5 mr-3">
                    <span>Keresés</span>
                </a>
            </nav>
            
            <div class="flex justify-center my-8">
                <img src="{{ asset('images/cat-dog.svg') }}" >
            </div>
            
            <div class="absolute bottom-0 w-64 border-t">
                <div class="px-4 py-3 hover:bg-blue-200 hover:rounded-lg flex items-center">
                    <img src="{{ asset('images/comment.svg') }}" class="h-5 w-5 mr-2">
                    <span class="text-[#333333]">Üzenetek</span>
                    <span class="ml-auto bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">3</span>
                </div>
            <a href="{{ route('settings.index') }}">
                <div class="px-4 py-3 hover:bg-blue-200 hover:rounded-lg flex items-center">
                    <img src="{{ asset('images/settings.svg') }}" class="h-5 w-5 mr-2">
                    <span class="text-[#333333]">Beállítások</span>
                </div>
            </a>
                

                <div class="px-4 py-3 hover:bg-blue-200 hover:rounded-lg">
                    <form action="{{ route('logout') }}" method="POST">
                        <button type="submit" class="flex flex-row" >
                            <img src="{{ asset('images/exit.svg') }}" class="h-5 w-5 mr-2">
                            @csrf
                            Logout
                        </button>
                    </form>
                </div>

                <div class="px-4 py-3 border-t flex items-center">
                        <div class="w-8 h-8 rounded-full mr-3">
                            <img src="{{ asset('images/profile.svg') }}" class="h-8 w-8 mr-2">
                        </div>
                    <div>
                        <p class="text-sm font-medium text-[#333333]">{{auth()->user()->name}}</p>
                        <p class="text-xs text-[#333333]">{{auth()->user()->email}}</p>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="w-16 h-screen bg-white shadow-md flex flex-col items-center py-4 transition-all duration-300 fixed top-0 left-0 z-10">
            <div class="mb-8">
                <div class="w-10 h-10 rounded-full flex items-center justify-center">
                    <img src="{{ asset('images/pet-svgrepo-com.svg') }}" class="h-10 w-10">
                </div>
            </div>
            
            <button wire:click="toggleSidebar" class="mb-8 text-gray-500 hover:text-gray-700 focus:outline-none">
                >
            </button>
            
            <div class="space-y-6 flex flex-col items-center">
                <a href="#" class="text-blue-500">
                    <img src="{{ asset('images/home.svg') }}" class="h-5 w-5 mr-2">
                </a>
                <a href="{{ route('shelters.index') }}" class="text-gray-500">
                    <img src="{{ asset('images/pet-shelter-svgrepo-com.svg') }}" class="h-5 w-5 mr-2">
                </a>
                <a href="#" class="text-gray-500">
                    <img src="{{ asset('images/pet-svgrepo-com.svg') }}" class="h-5 w-5 mr-2">
                </a>
                <a href="#" class="text-gray-500">
                    <img src="{{ asset('images/search.svg') }}" alt="" class="h-6 w-6">
                </a>
            </div>
            
            <div class="mt-auto space-y-6 flex flex-col">
                <a href="">
                    <img src="{{ asset('images/comment.svg') }}" class="h-5 w-5 mr-2">
                </a>
                <a href="{{ route('settings.index') }}" class="text-gray-500 relative">
                    <img src="{{ asset('images/settings.svg') }}" class="h-5 w-5 mr-2">
                    <span class="absolute top-0 right-0 -mt-1 -mr-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">3</span>
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    <button type="submit" class="flex flex-row" >
                            <img src="{{ asset('images/exit.svg') }}" class="h-5 w-5 mr-2">
                            @csrf
                    </button>
                </form>
                
                    <img src="{{ asset('images/profile.svg') }}" class="h-5 w-5 mr-2">
            </div>
        </div>
    @endif
</div>
<script>
    document.addEventListener('livewire:initialized', function () {
        Livewire.on('sidebarToggled', function (styleClass) {
            console.log('Sidebar toggled, new style class:', styleClass);
            
            const contentContainer = document.querySelector('.flex-1.p-6');
            
            if (contentContainer) {
                contentContainer.classList.remove('ml-64', 'ml-16');
                contentContainer.classList.add(styleClass);
            } else {
                console.error('Content container not found with selector: .flex-1.p-6');
            }
        });
    });
</script>