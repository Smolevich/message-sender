workflow "app-workflow" {
  on = "push"
  resolves = ["GitHub Action for Docker"]
}

action "GitHub Action for Docker" {
  uses = "./"
  runs = "composer run-test"
}
