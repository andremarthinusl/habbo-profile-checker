<?php
include 'vendor/autoload.php';
use HabboAPI\HabboAPI;
use HabboAPI\HabboParser;

$habboParser = new HabboParser('com');
$habboApi = new HabboAPI($habboParser);
$myProfile = null;

if (isset($_GET['habbo_name']) && !empty($_GET['habbo_name'])) {
    try {
        $myHabbo = $habboApi->getHabbo($_GET['habbo_name']);
        if ($myHabbo->hasProfile()) {
            $myProfile = $habboApi->getProfile($myHabbo->getId());
        }
    } catch (Exception $e) {
        $error = "Habbo not found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Habbo Profile Search</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-gray-900 text-gray-200">
    <div class="main-content">
        <div class="container mx-auto px-4 py-12 max-w-6xl">
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 rounded-xl shadow-habbo p-8 mb-10 border border-gray-700 glow-effect">
                <h1 class="text-4xl font-bold text-center text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500 mb-8">Habbo Profile Search</h1>
                <form method="GET" class="flex flex-col sm:flex-row gap-4 justify-center" id="searchForm">
                    <input 
                        type="text" 
                        name="habbo_name" 
                        class="flex-grow px-4 py-3 bg-gray-800 border border-gray-700 text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300" 
                        placeholder="Enter Habbo username..." 
                        value="<?php echo isset($_GET['habbo_name']) ? htmlspecialchars($_GET['habbo_name']) : ''; ?>"
                        required
                    >
                    <button type="submit" class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-bold py-3 px-8 rounded-lg transition duration-300 transform hover:scale-105 shadow-lg">Search Profile</button>
                </form>
                <div class="hidden mt-6 flex justify-center" id="loading">
                    <div class="w-10 h-10 border-4 border-blue-500 border-t-transparent rounded-full animate-spin shadow-lg"></div>
                </div>
            </div>

            <?php if (isset($error)): ?>
                <div class="bg-red-900/30 border border-red-500 text-red-400 p-5 mb-8 rounded-lg shadow-lg"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if ($myProfile): ?>
                <div class="bg-gradient-to-r from-gray-900 to-gray-800 rounded-xl shadow-habbo p-8 mb-10 border border-gray-700 glow-effect">
                    <div class="flex flex-col md:flex-row gap-6 items-center md:items-start">
                        <img 
                            class="w-32 h-32 rounded-lg object-cover"
                            src="https://www.habbo.com/habbo-imaging/avatarimage?figure=<?php echo $myHabbo->getFigureString(); ?>&size=l&direction=3&head_direction=3&gesture=sml&action=none" 
                            alt="<?php echo htmlspecialchars($myHabbo->getHabboName()); ?>"
                        >
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500 mb-1 text-center md:text-left"><?php echo htmlspecialchars($myHabbo->getHabboName()); ?></h2>
                            <p class="text-gray-400 italic mb-4 text-center md:text-left"><?php echo htmlspecialchars($myHabbo->getMotto()); ?></p>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                                <div class="bg-gray-800/50 p-4 rounded-lg border border-gray-700 hover:border-blue-500 transition-all duration-300">
                                    <div class="text-sm text-blue-400 font-medium">Member Since</div>
                                    <div class="text-gray-300 font-semibold"><?php echo $myHabbo->getMemberSince()->format('M j, Y'); ?></div>
                                </div>
                                <div class="bg-gray-800/50 p-4 rounded-lg border border-gray-700 hover:border-blue-500 transition-all duration-300">
                                    <div class="text-sm text-blue-400 font-medium">Last Access</div>
                                    <div class="text-gray-300 font-semibold"><?php echo $myHabbo->getLastAccessTime() ? $myHabbo->getLastAccessTime()->format('M j, Y') : 'N/A'; ?></div>
                                </div>
                                <div class="bg-gray-800/50 p-4 rounded-lg border border-gray-700 hover:border-blue-500 transition-all duration-300">
                                    <div class="text-sm text-blue-400 font-medium">Star Gems</div>
                                    <div class="text-gray-300 font-semibold"><?php echo number_format($myHabbo->getStarGemCount()); ?></div>
                                </div>
                                <div class="bg-gray-800/50 p-4 rounded-lg border border-gray-700 hover:border-blue-500 transition-all duration-300">
                                    <div class="text-sm text-blue-400 font-medium">Total Experience</div>
                                    <div class="text-gray-300 font-semibold"><?php echo number_format($myHabbo->getTotalExperience()); ?></div>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-3">
                                <?php if ($myHabbo->getCurrentLevel() >= 100): ?>
                                <div class="flex items-center bg-yellow-900/30 border border-yellow-600 px-3 py-2 rounded-full">
                                    <img class="w-6 h-6 mr-2" src="https://www.habbo.com/habbo-imaging/badge/XP100.gif" alt="Level 100+">
                                    <div class="text-sm font-medium text-yellow-400">Level <?php echo $myHabbo->getCurrentLevel(); ?></div>
                                </div>
                                <?php endif; ?>
                                <?php if ($myHabbo->isOnline()): ?>
                                <div class="flex items-center bg-green-900/30 border border-green-600 px-3 py-2 rounded-full">
                                    <img class="w-6 h-6 mr-2" src="./images/habbo_online.gif" alt="Online">
                                    <div class="text-sm font-medium text-green-400">Online Now</div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-xl shadow-lg p-6 border border-gray-700 card-hover">
                            <h2 class="text-xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500 mb-4 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 text-blue-500">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                Friends
                            </h2>
                            <ul class="space-y-4">
                                <?php foreach ($myProfile->getFriends() as $friend): ?>
                                    <li class="border border-gray-700 rounded-lg p-4 hover:bg-gray-800/50 hover:border-blue-500 transition-all duration-300">
                                        <div class="flex items-center mb-2">
                                            <img 
                                                class="w-10 h-10 rounded-full mr-3" 
                                                src="https://www.habbo.com/habbo-imaging/avatarimage?user=<?php echo urlencode($friend); ?>&headonly=1&direction=2&size=m&gesture=sml" 
                                                alt="<?php echo htmlspecialchars($friend); ?>"
                                            >
                                            <div>
                                                <div class="font-medium text-gray-300"><?php echo htmlspecialchars($friend); ?></div>
                                            </div>
                                        </div>
                                        <a href="?habbo_name=<?php echo urlencode($friend); ?>" class="text-sm text-blue-400 hover:text-blue-300 font-medium transition duration-300">View Habbo Info</a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>  
                    <div>
                        <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-xl shadow-lg p-6 border border-gray-700 card-hover">
                            <h2 class="text-xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500 mb-4 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 text-blue-500">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                                Groups
                            </h2>
                            <ul class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                <?php foreach ($myProfile->getGroups() as $group): ?>
                                    <li class="flex items-center border border-gray-700 rounded-lg p-3 hover:bg-gray-800/50 hover:border-blue-500 transition-all duration-300">
                                        <img 
                                            class="w-8 h-8 mr-2" 
                                            src="https://www.habbo.com/habbo-imaging/badge/<?php echo $group->getBadgeCode(); ?>.gif" 
                                            alt="<?php echo htmlspecialchars($group->getName()); ?>"
                                        >
                                        <span class="text-sm text-gray-300 truncate"><?php echo htmlspecialchars($group->getName()); ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <footer class="bg-gray-800 text-gray-300 py-6 text-center border-t border-gray-700 w-full">
        <div class="container mx-auto">
            <p>© <?php echo date('Y'); ?> <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500 font-semibold">HabboPortal</span> All rights reserved</p>
        </div>
    </footer>

    <script>
        document.getElementById('searchForm').addEventListener('submit', function() {
            document.getElementById('loading').classList.remove('hidden');
            document.getElementById('loading').classList.add('flex');
        });
    </script>
</body>
</html>