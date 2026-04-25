<x-guest-layout>
  @section('title', 'Tappmancs — Kezdőlap')

  <x-ui.home.container>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
      <div>
        <x-ui.home.h1>Tappmancs</x-ui.home.h1>
        <x-ui.home.h2>Fogadj örökbe kisállatot vagy indíts el a saját menhelyed!</x-ui.home.h2>

        <x-ui.home.lead>
          Csatlakozz egy olyan közösséghez, amely elkötelezett az állatok életének jobbá tétele iránt.
          Ismerd meg, hogyan segíthetsz, és kezdj hozzá most!
        </x-ui.home.lead>

        <div class="flex flex-col items-center">
          <x-ui.home.cta-button :href="route('shelters.index')" label="Tudj meg többet!" />

          <div class="flex gap-6 mt-12">
            <x-ui.home.stat value="1.3K+" label="Örökbefogadás" />
            <x-ui.home.stat value="30+" label="Menhely" />
          </div>
        </div>
      </div>

      <x-ui.home.image-frame
        :src="asset('images/berner.png')"
        alt="Örökbefogadás"
      />
    </div>

    <div class="flex items-center gap-10 mt-10">
      <x-ui.home.social-icon :src="asset('images/pet-svgrepo-com.svg')" :size="120" />
      <x-ui.home.social-icon :src="asset('images/facebook.svg')" :size="85" />
      <x-ui.home.social-icon :src="asset('images/insta.svg')" :size="85" />
      <x-ui.home.social-icon :src="asset('images/twitter.svg')" :size="85" />
    </div>
  </x-ui.home.container>
</x-guest-layout>
