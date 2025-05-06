@csrf

<div class="mb-3">
    <label>Photo</label>
    <input type="file" name="photo" class="form-control">
    @error('photo')
        <div class="text-danger">{{ $message }}</div>
    @enderror
    
    @if(!empty($profile->photo))
        <img src="{{ asset('storage/' . $profile->photo) }}" width="100" class="mt-2">
    @endif
</div>

<div class="mb-3">
    <label>Name</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $profile->name ?? '') }}" required>
    @error('name')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label>Email</label>
    <input type="email" name="email" class="form-control" value="{{ old('email', $profile->email ?? '') }}" required>
    @error('email')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label>Phone Number</label>
    <input type="text" name="phone" class="form-control" value="{{ old('phone', $profile->phone ?? '') }}">
    @error('phone')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label>Address</label>
    <textarea name="address" class="form-control">{{ old('address', $profile->address ?? '') }}</textarea>
    @error('address')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label>Price Range</label>
    <select name="price_range" class="form-control">
        <option value="">-- Select --</option>
        @php 
            $priceRanges = ['500 Ribu - 1 Juta /bulan', '1 Juta - 2 Juta /bulan', '2 Juta - 3 Juta /bulan'];
        @endphp
        @foreach($priceRanges as $range)
            <option value="{{ $range }}" {{ old('price_range', $profile->price_range ?? '') == $range ? 'selected' : '' }}>{{ $range }}</option>
        @endforeach
    </select>
    @error('price_range')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label>Preferred Location</label>
    <input type="text" name="location" class="form-control" value="{{ old('location', $profile->location ?? '') }}">
    @error('location')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label>Preferred Room Type</label>
    <select name="room_type" class="form-control">
        <option value="">-- Select --</option>
        @php
            $roomTypes = ['Kosan Pria dan Wanita', 'Kosan Khusus Pria', 'Kosan Khusus Wanita'];
        @endphp
        @foreach($roomTypes as $type)
            <option value="{{ $type }}" {{ old('room_type', $profile->room_type ?? '') == $type ? 'selected' : '' }}>{{ $type }}</option>
        @endforeach
    </select>
    @error('room_type')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label>Preferred Facilities</label><br>
    
    @php
        $allFacilities = ['Kasur', 'Lemari Pakaian', 'Meja belajar dan Kursi', 'Kamar mandi dalam', 'Cermin', 'Rak Sepatu', 'Rak Buku', 'AC', 'Wifi', 'Dapur bersama', 'Dispenser', 'Ruang jemur pakaian', 'Ruang tamu', 'CCTV', 'Mushola'];
        
        // Pastikan kita memiliki array, bukan string
        $selectedFacilities = [];
        
        if (isset($profile) && !empty($profile->facilities)) {
            $selectedFacilities = explode(',', $profile->facilities);
        } elseif (old('facilities')) {
            $selectedFacilities = old('facilities');
        }
    @endphp
    
    @foreach($allFacilities as $facility)
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="facilities[]" value="{{ $facility }}"
                {{ in_array($facility, $selectedFacilities) ? 'checked' : '' }}>
            <label class="form-check-label">{{ $facility }}</label>
        </div>
    @endforeach
    
    @error('facilities')
        <div class="text-danger">{{ $message }}</div>
    @enderror
    @error('facilities.*')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<button type="submit" class="btn btn-success">Save</button>

@if(session('success'))
    <div class="alert alert-success mt-3">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger mt-3">
        {{ session('error') }}
    </div>
@endif