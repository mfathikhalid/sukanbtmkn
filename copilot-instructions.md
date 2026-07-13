# GitHub Copilot Instructions



## Project Overview



This is a Laravel 12 web application for managing a sports carnival.



The system is intended for internal use by administrators only.



Use:

- Laravel 12

- PHP 8.3+

- Blade

- Bootstrap 5

- MySQL

- Laravel Authentication (basic login only)

- No API

- No Livewire

- No Inertia

- No Vue

- No React

- No Spatie Permission



Always follow Laravel best practices.



---



# Coding Standards



- Follow PSR-12.

- Use Service classes for business logic.

- Controllers should remain thin.

- Use Form Request validation.

- Use Eloquent relationships.

- Never duplicate business logic.

- Use Transactions where required.

- Use route model binding.

- Prefer dependency injection.

- Use Carbon for date handling.

- Use Enums where appropriate.

- Keep methods small and readable.

- Avoid raw SQL unless absolutely necessary.



---



# Authentication



There is only one type of user:



- Admin



Use Laravel's built-in authentication.



No roles.

No permissions.



Every authenticated user has full access.



---



# Modules



## Dashboard



Display:



- Total Participants

- Total Events

- Participants without any event

- Total Matches

- Completed Matches

- Current House Ranking



---



## Houses



There are exactly four houses.



They are fixed and cannot be edited.



- Merah

- Hijau

- Biru

- Kuning



Store them in the database.



Do not allow create, update or delete.



---



## Participants



Fields



- id

- house_id

- employee_no

- name

- gender

- department (nullable)

- created_at

- updated_at



Rules



- employee_no must be unique.

- Every participant belongs to one house.

- Gender is Male or Female.



Functions



- Create

- Edit

- Delete

- Search

- Filter by house

- Filter by gender



---



## Sports

Sports are fixed.

### League Sports

- FIFA

- Tekken

- Pickleball

- Congkak

- Carrom

- Dart

### Bowling

Bowling is handled differently because winners are determined by total pin count.



---



## Event Registration



Each participant may join one or more events.



Minimum participation:



At least one event.



No maximum limit.



Prevent duplicate registration in the same event.



Each event has gender quotas.



Example



FIFA



Male: 2



Female: 0



Tekken



Male: 0



Female: 2



Pickleball



Male: 2



Female: 2



Congkak



Male: 3



Female: 3



Carrom



Male: 2



Female: 2



Dart



Male: 3



Female: 3



Dart is scored as a house team event.

Each house sends 3 registered participants for the relevant gender category.

Do not divide participants into A, B, or C match slots.

The three participant scores are added together to produce one house score.

The house with the higher combined score wins the match.

Dart uses one 501 game per match.

Do not use legs or Best of 3.

Store only the winning and losing house result.



Bowling



Male: 2



Female: 2



The system must validate quotas before saving.



---



## League Matches



Every league sport has four houses.



Generate fixtures automatically using round robin.



Fixtures:



Merah vs Hijau



Biru vs Kuning



Merah vs Biru



Hijau vs Kuning



Merah vs Kuning



Hijau vs Biru



Each match stores



- home_house

- away_house

- home_score

- away_score

- winner

- played_at

Admins do not enter match scores for league events.

Use a dropdown to select either the home house or away house as the winner.

Store the selected winner and an internal 1-0 or 0-1 result for standings calculations.



League points



Win = 1



Draws are not allowed. Every match must have a winner.



Lose = 0



Calculate automatically.



Display standings sorted by:



1. Points

2. Goal Difference

3. Goals Scored



---



## Bowling



Each house registers



- 2 Male

- 2 Female

Each registered player plays exactly 2 games.



Store the score for Game 1 and Game 2 separately for every player.



Calculate



Player Total = Game 1 + Game 2.

House Total = Sum of all player totals.



Ranking



Highest total wins.



---



## House Points



Every completed event awards house points by final position.



1st = 10 points.

2nd = 7 points.

3rd = 5 points.

4th = 3 points.

League event positions are determined after the Final and Third Place match.

Bowling positions are determined by the highest total pin fall after every registered player completes both games.



Overall scoreboard is calculated from all events.



Never store calculated totals.



Always calculate from source data.



---



## Scoreboard

Provide a public read-only live page at `/live` without authentication.

The public page displays the overall scoreboard, event points, and all knockout brackets.

It must refresh automatically every 5 seconds and must never expose admin forms or mutation actions.



Display



Overall Ranking



Columns



- Rank

- House

- Total Points



Clicking a house shows



- Event Results

- Event Points

- Bowling Scores

- League Positions



---



# Database Design



users



id

name

email

password



houses



id

name

color



participants



id

house_id

employee_no

name

gender

department



sports



id

name

type



sport_registrations



id

sport_id

participant_id



matches



id

sport_id

home_house_id

away_house_id

match_date



match_results



id

match_id

home_score

away_score



bowling_scores



id

sport_id

participant_id

score



point_settings



id

position

points



---



# Relationships



House



hasMany Participants



Participant



belongsTo House



belongsToMany Sports



Sport



belongsToMany Participants



hasMany Matches



hasMany BowlingScores



Match



belongsTo Sport



belongsTo House (home)



belongsTo House (away)



hasOne MatchResult



---



# Validation Rules



Participant



employee_no must be unique.



Registration



Cannot register same participant twice in same sport.



Gender quota cannot exceed sport limit.



House quota cannot exceed sport limit.



Match



Cannot enter result twice.



Bowling



Score must be numeric.



Score >= 0.



---



# UI Guidelines



Use Bootstrap 5.

Dashboard, event pages, Dart, Bowling, and Scoreboard use live polling every 5 seconds.

Pause live refresh while an administrator is editing a form so unsaved input is preserved.

Use Blade and vanilla JavaScript for live updates. Do not introduce Livewire, Vue, or React.



Use Blade components where appropriate.



Use DataTables for listing pages.



Use Bootstrap Modal for confirmations.



Use SweetAlert2 for delete confirmation.



Display success messages using Bootstrap alerts.



Use badges for:



- Male

- Female

- House

- Match Status



---



# Performance



Always eager load relationships.



Avoid N+1 queries.



Paginate long lists.



Use database indexes for:



- employee_no

- house_id

- sport_id

- participant_id



---



# General Rules



Never hardcode IDs.



Never duplicate business logic.



Use database transactions for critical operations.



Return proper validation errors.



Keep controllers small.



Business logic belongs in Services.



Calculations belong in dedicated service classes.



Write readable, maintainable production-ready code.



## Seed Data



### Houses



| Name | Color |

|------|--------|

| Merah | red |

| Hijau | green |

| Biru | blue |

| Kuning | yellow |



### Sports



| Name | Type |

|------|-------|

| FIFA | league |

| Tekken | league |

| Pickleball | league |

| Congkak | league |

| Carrom | league |

| Dart | league |

| Bowling | bowling |



### Sport Quotas



| Sport | Male | Female |

|--------|------|--------|

| FIFA | 2 | 0 |

| Tekken | 0 | 2 |

| Pickleball | 2 | 2 |

| Congkak | 3 | 3 |

| Carrom | 2 | 2 |

| Dart | 3 | 3 |

| Bowling | 2 | 2 |
