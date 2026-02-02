<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
class ProfileController extends Controller
{
     public function profile(Request $request)
    {
        $user = Auth::user();

        if ($request->method() == "POST") {
            if ($request->has('action')) {
                switch ($request->action) {
                    case 'update_profile':
                        return $this->updateProfile($request, $user);
                    case 'update_email':
                        return $this->updateEmail($request, $user);
                    case 'update_password':
                        return $this->updatePassword($request, $user);
                    default:
                        return response()->json([
                            'success' => false,
                            'message' => 'Invalid action'
                        ], 400);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'No action specified'
            ], 400);

        } else {
            return view('customer.profile.index', compact('user'));
        }
    }

    private function updateProfile(Request $request, $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user->name = $request->name;
            $additionalFields = ['phone', 'address', 'city', 'country', 'postal_code'];
            foreach ($additionalFields as $field) {
                if ($request->has($field)) {
                    $user->$field = $request->$field;
                }
            }

            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully!',
                'user' => $user->only(['name', 'email', 'phone', 'address', 'city', 'country', 'postal_code', 'created_at'])
            ]);

        } catch (\Exception $e) {
            \Log::error('Profile update error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    private function updateEmail(Request $request, $user)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 401);
        }

        try {
            $oldEmail = $user->email;
            $user->email = $request->new_email;
            $user->email_verified_at = null; // Require re-verification
            $user->save();

            // Send email verification notification
            // $user->sendEmailVerificationNotification();

            \Log::info('User email changed', [
                'user_id' => $user->id,
                'old_email' => $oldEmail,
                'new_email' => $request->new_email
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Email updated successfully! Please verify your new email address.'
            ]);

        } catch (\Exception $e) {
            \Log::error('Email update error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update email. Please try again.'
            ], 500);
        }
    }

    private function updatePassword(Request $request, $user)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ],
            'new_password_confirmation' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 401);
        }

        // Check if new password is same as current
        if (Hash::check($request->new_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'New password must be different from current password'
            ], 422);
        }

        try {
            $user->password = Hash::make($request->new_password);
            $user->save();

            // Optionally, logout other devices
            // Auth::logoutOtherDevices($request->new_password);

            \Log::info('User password changed', ['user_id' => $user->id]);

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Password update error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update password. Please try again.'
            ], 500);
        }
    }

    // Add a method to get user profile data via API
    public function getProfileData()
    {
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'email_verified' => $user->hasVerifiedEmail(),
                'created_at' => $user->created_at->format('F j, Y'),
                'phone' => $user->phone ?? 'Not set',
                'address' => $user->address ?? 'Not set',
                'city' => $user->city ?? 'Not set',
                'country' => $user->country ?? 'Not set',
                'postal_code' => $user->postal_code ?? 'Not set',
            ]
        ]);
    }
    public function deleteAccount(Request $request)
    {
        $user = Auth::user();

        // Verify user password for security
        $validator = Validator::make($request->all(), [
            'password' => 'required|current_password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Password is incorrect'
            ], 401);
        }

        try {
            // Log the user out
            Auth::logout();

            // Soft delete if you want to keep records
            // $user->delete();

            // Or hard delete
            $user->forceDelete();

            // Invalidate session
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json([
                'success' => true,
                'message' => 'Account deleted successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Account deletion error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete account. Please try again.'
            ], 500);
        }
    }
}
