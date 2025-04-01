<?php

namespace App\Infrastructure\Persistence;

use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\Entities\User as DomainUser;
use App\Models\User as EloquentUser;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function find(int $id): ?DomainUser
    {
        $user = EloquentUser::find($id);
        if (!$user) {
            return null;
        }
        return new DomainUser(
            $user->id,
            $user->name,
            $user->surname,
            $user->email,
            $user->phone,
            $user->country,
            $user->gender,
            $user->password,
            $user->selfie,
            $user->introduction
        );
    }

    public function findAll(): array
    {
        $users = EloquentUser::all();
        $domainUsers = [];
        foreach ($users as $user) {
            $domainUsers[] = new DomainUser(
                $user->id,
                $user->name,
                $user->surname,
                $user->email,
                $user->phone,
                $user->country,
                $user->gender,
                $user->password,
                $user->selfie,
                $user->introduction
            );
        }
        return $domainUsers;
    }

    public function save(DomainUser $user): DomainUser
    {
        $eloquentUser = EloquentUser::create([
            'name'         => $user->getName(),
            'surname'      => $user->getSurname(),
            'email'        => $user->getEmail(),
            'phone'        => $user->getPhone(),
            'country'      => $user->getCountry(),
            'gender'       => $user->getGender(),
            'password'     => $user->getPassword(),
            'selfie'       => $user->getSelfie(),
            'introduction' => $user->getIntroduction(),
        ]);
        return new DomainUser(
            $eloquentUser->id,
            $eloquentUser->name,
            $eloquentUser->surname,
            $eloquentUser->email,
            $eloquentUser->phone,
            $eloquentUser->country,
            $eloquentUser->gender,
            $eloquentUser->password,
            $eloquentUser->selfie,
            $eloquentUser->introduction
        );
    }

    public function update(DomainUser $user): DomainUser
    {
        $eloquentUser = EloquentUser::find($user->getId());
        $data = [
            'name'         => $user->getName(),
            'surname'      => $user->getSurname(),
            'email'        => $user->getEmail(),
            'phone'        => $user->getPhone(),
            'country'      => $user->getCountry(),
            'gender'       => $user->getGender(),
            'password'     => $user->getPassword(),
            'selfie'       => $user->getSelfie(),
            'introduction' => $user->getIntroduction(),
        ];
        $eloquentUser->update($data);
        return new DomainUser(
            $eloquentUser->id,
            $eloquentUser->name,
            $eloquentUser->surname,
            $eloquentUser->email,
            $eloquentUser->phone,
            $eloquentUser->country,
            $eloquentUser->gender,
            $eloquentUser->password,
            $eloquentUser->selfie,
            $eloquentUser->introduction
        );
    }

    public function delete(DomainUser $user): bool
    {
        $eloquentUser = EloquentUser::find($user->getId());
        return $eloquentUser ? $eloquentUser->delete() : false;
    }
}
