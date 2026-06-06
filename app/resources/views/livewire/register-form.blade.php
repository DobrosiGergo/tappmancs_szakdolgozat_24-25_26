<form wire:submit="register" class="space-y-8">

  <div class="relative">
    <input
      type="email"
      id="reg-email"
      wire:model.live.debounce.600ms="email"
      placeholder="Email"
      autocomplete="username"
      required
      class="peer block w-full border-0 border-b border-neutral-400 bg-transparent
             focus:border-neutral-900 focus:ring-0 placeholder-transparent"
    />
    <label for="reg-email"
           class="absolute left-0 -top-3.5 text-sm text-neutral-600 transition-all
                  peer-placeholder-shown:top-2 peer-placeholder-shown:text-base peer-placeholder-shown:text-neutral-400
                  peer-focus:-top-3.5 peer-focus:text-sm peer-focus:text-neutral-900">
      Email
    </label>
    @if($emailTaken)
      <p class="mt-1.5 text-xs text-red-500">Ez az e-mail cím már foglalt.</p>
    @endif
    <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
  </div>

  <div class="relative">
    <input
      type="text"
      id="reg-name"
      wire:model="name"
      placeholder="Teljes név"
      autocomplete="name"
      required
      class="peer block w-full border-0 border-b border-neutral-400 bg-transparent
             focus:border-neutral-900 focus:ring-0 placeholder-transparent"
    />
    <label for="reg-name"
           class="absolute left-0 -top-3.5 text-sm text-neutral-600 transition-all
                  peer-placeholder-shown:top-2 peer-placeholder-shown:text-base peer-placeholder-shown:text-neutral-400
                  peer-focus:-top-3.5 peer-focus:text-sm peer-focus:text-neutral-900">
      Teljes név
    </label>
    <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
  </div>

  <div x-data="{
    pw: '',
    confirmPw: '',
    get matches() { return this.confirmPw.length > 0 && this.confirmPw === this.pw; },
    get strength() {
      if (this.pw.length === 0) return '';
      if (this.pw.length < 8) return 'gyenge';
      let score = 0;
      if (/[A-Z]/.test(this.pw)) score++;
      if (/[0-9]/.test(this.pw)) score++;
      if (/[^A-Za-z0-9]/.test(this.pw)) score++;
      if (this.pw.length >= 12) score++;
      if (score >= 3) return 'erős';
      if (score >= 1) return 'közepes';
      return 'gyenge';
    },
  }" class="space-y-8">

    <div class="relative">
      <input
        type="password"
        id="reg-password"
        wire:model.lazy="password"
        x-on:input="pw = $event.target.value"
        placeholder="Jelszó"
        autocomplete="new-password"
        required
        class="peer block w-full border-0 border-b border-neutral-400 bg-transparent
               focus:border-neutral-900 focus:ring-0 placeholder-transparent"
      />
      <label for="reg-password"
             class="absolute left-0 -top-3.5 text-sm text-neutral-600 transition-all
                    peer-placeholder-shown:top-2 peer-placeholder-shown:text-base peer-placeholder-shown:text-neutral-400
                    peer-focus:-top-3.5 peer-focus:text-sm peer-focus:text-neutral-900">
        Jelszó
      </label>

      <div x-cloak x-show="strength !== ''" class="mt-2.5 flex items-center gap-2.5">
        <div class="flex flex-1 gap-1.5">
          <div class="h-1 flex-1 rounded-full transition-colors duration-300"
               :class="{'bg-red-400': strength !== '', 'bg-neutral-200': strength === ''}"></div>
          <div class="h-1 flex-1 rounded-full transition-colors duration-300"
               :class="{'bg-amber-400': strength === 'közepes' || strength === 'erős', 'bg-neutral-200': strength === 'gyenge' || strength === ''}"></div>
          <div class="h-1 flex-1 rounded-full transition-colors duration-300"
               :class="{'bg-green-500': strength === 'erős', 'bg-neutral-200': strength !== 'erős'}"></div>
        </div>
        <span x-text="strength" class="w-12 text-right text-xs text-neutral-500"></span>
      </div>

      <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
    </div>

    <div class="relative">
      <input
        type="password"
        id="reg-password-confirm"
        wire:model.lazy="password_confirmation"
        x-on:input="confirmPw = $event.target.value"
        placeholder="Jelszó megerősítése"
        autocomplete="new-password"
        required
        class="peer block w-full border-0 border-b border-neutral-400 bg-transparent
               focus:border-neutral-900 focus:ring-0 placeholder-transparent"
      />
      <label for="reg-password-confirm"
             class="absolute left-0 -top-3.5 text-sm text-neutral-600 transition-all
                    peer-placeholder-shown:top-2 peer-placeholder-shown:text-base peer-placeholder-shown:text-neutral-400
                    peer-focus:-top-3.5 peer-focus:text-sm peer-focus:text-neutral-900">
        Jelszó megerősítése
      </label>
      <p x-cloak x-show="confirmPw.length > 0 && !matches" class="mt-1.5 text-xs text-red-500">
        A két jelszó nem egyezik.
      </p>
      <p x-cloak x-show="matches" class="mt-1.5 text-xs text-green-600">
        A jelszavak egyeznek.
      </p>
    </div>

  </div>

  <div class="relative">
    <input
      type="text"
      id="reg-phone"
      wire:model="phoneNumber"
      placeholder="Telefonszám +36-"
      autocomplete="tel"
      class="peer block w-full border-0 border-b border-neutral-400 bg-transparent
             focus:border-neutral-900 focus:ring-0 placeholder-transparent"
    />
    <label for="reg-phone"
           class="absolute left-0 -top-3.5 text-sm text-neutral-600 transition-all
                  peer-placeholder-shown:top-2 peer-placeholder-shown:text-base peer-placeholder-shown:text-neutral-400
                  peer-focus:-top-3.5 peer-focus:text-sm peer-focus:text-neutral-900">
      Telefonszám +36-
    </label>
    <x-input-error :messages="$errors->get('phoneNumber')" class="mt-1.5" />
  </div>

  <button
    type="submit"
    class="w-full mt-2 bg-neutral-900 text-white py-3 rounded-full font-medium hover:bg-neutral-800 transition"
  >
    <span wire:loading.remove wire:target="register">Fiók létrehozása</span>
    <span wire:loading wire:target="register" class="inline-flex items-center gap-2">
      <x-icon name="spinner" class="h-4 w-4 animate-spin" />
      Folyamatban...
    </span>
  </button>

</form>
