@component('mail::message')
# Nouveau prospect identifié

**Numéro** : {{ $numero }}  
**Signalé par** : {{ $source }}  
**Date** : {{ now()->format('d/m/Y H:i') }}

@component('mail::panel')
Ce numéro a été identifié comme potentiel client pour nos services bancaires.
@endcomponent

**Actions recommandées** :
1. Vérifier les antécédents
2. Contacter sous 48h
3. Proposer l'offre Starter

@endcomponent